<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\SalaryCalculation;
use App\Models\User;
use App\Services\FreeGeocodingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function login()
    {
        return view('employee.login');
    }

    public function dashboard(string $employeeId)
    {
        if (session('employee_id') !== $employeeId) {
            return redirect()->route('employee.login');
        }

        $employee = Employee::with('branch')->where('employee_id', $employeeId)->first();

        if (!$employee) {
            return redirect()->route('employee.login');
        }

        $photo = $employee->photo
            ? asset('employee_profile_pic/' . $employee->photo)
            : 'https://i.pravatar.cc/64?img=' . ($employee->id % 60);

        $today = Carbon::today();
        $now = Carbon::now();

        $user = User::where('email', $employee->email)->first();

        $todayAtt = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('date', $today)
            ->first();

        $attendances = Attendance::where('employee_id', $employee->employee_id)
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $monthAtt = Attendance::where('employee_id', $employee->employee_id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $presentCount = $monthAtt->where('status', 'present')->count();
        $halfDayCount = $monthAtt->where('status', 'half-day')->count();
        $absentCount = $monthAtt->where('status', 'absent')->count();
        $onLeaveCount = $monthAtt->where('status', 'On Leave')->count();

        $leaves = LeaveRequest::where(function ($q) use ($employee, $user) {
            $q->where('employee_id', $employee->employee_id);
            if ($user) {
                $q->orWhere('user_id', $user->id);
            }
        })->latest()->get();

        $approvedLeaves = $leaves->where('status', 'Approved');
        $pendingLeaves = $leaves->where('status', 'Pending');

        $paidLeavesTotal = $employee->paid_leaves ?? 1;
        $usedLeaveDays = 0;
        foreach ($approvedLeaves as $leave) {
            $usedLeaveDays += Carbon::parse($leave->from_date)->diffInDays(Carbon::parse($leave->to_date)) + 1;
        }
        $remainingLeaves = max(0, $paidLeavesTotal - $usedLeaveDays);

        $notifications = Notification::where(function ($q) use ($employee, $user) {
            $q->where('employee_id', $employee->employee_id);
            if ($user) {
                $q->orWhere('user_id', $user->id);
            }
        })->latest()->get();
        $unreadNotifications = $notifications->where('is_read', false)->count();

        $baseSalary = $employee->salary ?? 0;
        $approvedLeaveDaysThisMonth = 0;
        foreach ($approvedLeaves as $leave) {
            $from = Carbon::parse($leave->from_date);
            $to = Carbon::parse($leave->to_date);
            if ($from->lte($monthEnd) && $to->gte($monthStart)) {
                $clampedFrom = $from->lt($monthStart) ? $monthStart->copy() : $from;
                $clampedTo = $to->gt($monthEnd) ? $monthEnd->copy() : $to;
                $approvedLeaveDaysThisMonth += $clampedFrom->diffInDays($clampedTo) + 1;
            }
        }

        $perDay = $baseSalary / 30;

        $joinDate = $employee->join_date ? Carbon::parse($employee->join_date) : null;
        if ($joinDate && $joinDate->gt($monthStart)) {
            $eligibleDays = min(30, (int)$joinDate->copy()->startOfDay()->diffInDays($monthEnd->copy()->startOfDay()) + 1);
        } else {
            $eligibleDays = 30;
        }

        $workedDays = $presentCount + ($halfDayCount * 0.5) + $approvedLeaveDaysThisMonth;
        $workedDays = min($workedDays, $eligibleDays);
        $deductibleDays = max(0, $eligibleDays - $workedDays);
        $finalSalary = max(0, round($perDay * $workedDays, 2));

        $salaryRecords = SalaryCalculation::where('employee_id', $employee->id)
            ->latest()
            ->limit(6)
            ->get();

        return view('employee.dashboard', compact(
            'employee', 'photo', 'todayAtt', 'attendances',
            'presentCount', 'halfDayCount', 'absentCount', 'onLeaveCount',
            'leaves', 'pendingLeaves', 'approvedLeaves',
            'paidLeavesTotal', 'usedLeaveDays', 'remainingLeaves',
            'notifications', 'unreadNotifications',
            'baseSalary', 'approvedLeaveDaysThisMonth', 'perDay',
            'eligibleDays', 'workedDays',
            'deductibleDays', 'finalSalary', 'salaryRecords',
            'now', 'monthStart', 'monthEnd'
        ));
    }

    public function storeLeaveRequest(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'type' => 'required|string|max:100',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $user = User::where('email', $employee->email)->first();

        $leave = LeaveRequest::create([
            'user_id' => $user?->id,
            'employee_id' => $employee->employee_id,
            'type' => $request->type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'New Leave Request',
                'body' => $employee->name . ' requested ' . $request->type
                    . ' from ' . $request->from_date . ' to ' . $request->to_date,
                'type' => 'bi-calendar-event-fill',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave request submitted successfully.',
            'leave' => $leave,
        ]);
    }

    public function markNotificationsRead(Request $request): JsonResponse
    {
        $request->validate(['employee_id' => 'required|string|exists:employees,employee_id']);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $user = User::where('email', $employee->email)->first();

        Notification::where(function ($q) use ($employee, $user) {
            $q->where('employee_id', $employee->employee_id);
            if ($user) {
                $q->orWhere('user_id', $user->id);
            }
        })->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'mobile' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $employee->update($request->only(['mobile', 'emergency_contact', 'state', 'city', 'blood_group']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }

    public function lookup(Request $request): JsonResponse
    {
        $request->validate(['employee_id' => 'required|string']);

        $id = strtoupper(trim($request->employee_id));

        $employee = Employee::with('branch')
            ->whereRaw('UPPER(employee_id) = ?', [$id])
            ->orWhereRaw('UPPER(REPLACE(REPLACE(REPLACE(employee_id, "-", ""), " ", ""), "_", "")) = ?', [preg_replace('/[\s_\-]/', '', $id)])
            ->first();

        if (!$employee) {
            return response()->json(['found' => false, 'message' => 'Employee not found.'], 404);
        }

        if ($employee->status === 'Deactivated') {
            return response()->json(['found' => false, 'message' => 'This account has been deactivated. Contact administrator.'], 403);
        }

        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('date', $today)
            ->first();

        $request->session()->put('employee_id', $employee->employee_id);

        return response()->json([
            'found' => true,
            'employee' => [
                'id' => $employee->employee_id,
                'name' => $employee->name,
                'designation' => $employee->designation,
                'branch' => $employee->branch?->name ?? '',
                'img' => $employee->photo
                    ? asset('employee_profile_pic/' . $employee->photo)
                    : 'https://i.pravatar.cc/64?img=' . ($employee->id % 60),
            ],
            'attendance' => $attendance ? [
                'check_in' => $attendance->check_in ? Carbon::parse($attendance->check_in)->format('h:i:s A') : null,
                'check_out' => $attendance->check_out ? Carbon::parse($attendance->check_out)->format('h:i:s A') : null,
            ] : null,
        ]);
    }

    public function clockIn(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_name' => 'nullable|string|max:255',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        $today = Carbon::today();
        $existing = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->check_in) {
            return response()->json(['success' => false, 'message' => 'Already clocked in today.'], 422);
        }

        $now = Carbon::now();
        $status = $now->format('H:i') >= '12:00' ? 'half-day' : 'present';

        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $today],
            [
                'check_in' => $now,
                'status' => $status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'location_name' => $request->location_name,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Clock In recorded at ' . $now->format('h:i:s A'),
            'time' => $now->format('h:i:s A'),
        ]);
    }

    public function clockOut(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string',
            'daily_report' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_name' => 'nullable|string|max:255',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Not clocked in yet. Please clock in first.'], 422);
        }

        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'Already clocked out today.'], 422);
        }

        $now = Carbon::now();
        $checkIn = Carbon::parse($attendance->check_in);
        $checkOutTime = $now->format('H:i');

        if ($checkIn->format('H:i') >= '12:00') {
            $status = 'half-day';
        } elseif ($checkOutTime < '13:30') {
            $status = 'absent';
        } elseif ($checkOutTime < '18:30') {
            $status = 'half-day';
        } else {
            $status = 'present';
        }

        $attendance->update([
            'check_out' => $now,
            'status' => $status,
            'daily_report' => $request->daily_report,
            'latitude' => $request->latitude ?? $attendance->latitude,
            'longitude' => $request->longitude ?? $attendance->longitude,
            'location_name' => $request->location_name ?? $attendance->location_name,
        ]);

        $hoursWorked = $checkIn->diffInHours($now) . 'h ' . ($checkIn->diffInMinutes($now) % 60) . 'm';

        return response()->json([
            'success' => true,
            'message' => 'Clock Out recorded at ' . $now->format('h:i:s A'),
            'time' => $now->format('h:i:s A'),
            'hours_worked' => $hoursWorked,
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('employee_id');

        return redirect('/');
    }

    public function dailyReports(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'nullable|date',
            'month' => 'nullable|date_format:Y-m',
        ]);

        $employeeId = $request->session()->get('employee_id');

        if (!$employeeId) {
            return response()->json(['success' => false, 'message' => 'Not logged in.'], 401);
        }

        $query = Attendance::where('employee_id', $employeeId);

        if ($request->date) {
            $query->whereDate('date', Carbon::parse($request->date));
        } elseif ($request->month) {
            $monthStart = Carbon::parse($request->month . '-01')->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $query->whereBetween('date', [$monthStart, $monthEnd]);
        }

        $records = $query->orderByDesc('date')->get()->map(function ($a) {
            $hours = '--';
            if ($a->check_in && $a->check_out) {
                $minutes = Carbon::parse($a->check_in)->diffInMinutes(Carbon::parse($a->check_out));
                $hours = intdiv($minutes, 60) . 'h ' . ($minutes % 60) . 'm';
            }

            return [
                'date' => $a->date->toDateString(),
                'check_in' => $a->check_in ? Carbon::parse($a->check_in)->format('h:i A') : '--',
                'check_out' => $a->check_out ? Carbon::parse($a->check_out)->format('h:i A') : '--',
                'hours' => $hours,
                'status' => ucfirst($a->status),
                'report' => $a->daily_report ?? '',
            ];
        });

        return response()->json($records);
    }

    public function addressFromCoords(Request $request, FreeGeocodingService $geocoder): JsonResponse
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $address = $geocoder->getAddressFromCoords((float)$data['lat'], (float)$data['lng']);

        return response()->json([
            'address' => $address,
        ]);
    }
}
