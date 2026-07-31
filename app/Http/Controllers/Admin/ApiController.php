<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\SalaryCalculation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    const SHIFT_START = '09:30';
    const SHIFT_END = '18:30';

    public function dashboardStats(): JsonResponse
    {
        $total = Employee::count();
        $today = Carbon::today();
        $presentToday = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $absent = Attendance::whereDate('date', $today)->where('status', 'absent')->count();
        $onLeave = LeaveRequest::where('status', 'Approved')
            ->whereDate('from_date', '<=', $today)
            ->whereDate('to_date', '>=', $today)
            ->count();
        $pending = LeaveRequest::where('status', 'Pending')->count();

        return response()->json([
            'total' => $total ?: 0,
            'present' => $presentToday ?: 0,
            'onLeave' => $onLeave ?: 0,
            'absent' => $absent ?: 0,
            'pending' => $pending ?: 0,
        ]);
    }

    public function employees(): JsonResponse
    {
        $employees = Employee::with('branch')->get()->map(function ($e) {
            $img = $e->photo
                ? asset('employee_profile_pic/' . $e->photo)
                : 'https://i.pravatar.cc/64?img=' . ($e->id % 60);
            return [
                'id' => $e->employee_id,
                'name' => $e->name,
                'designation' => $e->designation,
                'branch' => $e->branch?->name ?? '',
                'shiftStart' => $e->shift_start,
                'shiftEnd' => $e->shift_end,
                'email' => $e->email,
                'status' => $e->status,
                'gender' => $e->gender,
                'age' => $e->age,
                'dob' => $e->dob?->format('Y-m-d'),
                'join_date' => $e->join_date?->format('Y-m-d'),
                'salary' => $e->salary,
                'blood_group' => $e->blood_group ?? '',
                'mobile' => $e->mobile ?? '',
                'emergency_contact' => $e->emergency_contact ?? '',
                'state' => $e->state ?? '',
                'city' => $e->city ?? '',
                'paid_leaves' => $e->paid_leaves ?? 1,
                'img' => $img,
            ];
        });

        return response()->json($employees);
    }

    public function storeEmployee(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|string|max:20|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'designation' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'shiftStart' => 'nullable',
            'shiftEnd' => 'nullable',
            'gender' => 'nullable|string',
            'age' => 'nullable|integer',
            'dob' => 'nullable|date',
            'join_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'paid_leaves' => 'nullable|integer|min:0',
            'blood_group' => 'nullable|string|max:10',
        ]);

        $branchId = null;
        if (!empty($data['branch'])) {
            $branch = Branch::firstOrCreate(['name' => $data['branch']]);
            $branchId = $branch->id;
        }

        if (!empty($data['designation'])) {
            Designation::firstOrCreate(['title' => $data['designation']], ['department' => 'General']);
        }

        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('employee_profile_pic'), $photoName);
        }

        $employee = Employee::create([
            'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'designation' => $data['designation'],
            'branch_id' => $branchId,
            'shift_start' => $data['shiftStart'] ?? null,
            'shift_end' => $data['shiftEnd'] ?? null,
            'gender' => $data['gender'] ?? null,
            'age' => $data['age'] ?? null,
            'dob' => $data['dob'] ?? null,
            'join_date' => $data['join_date'] ?? null,
            'photo' => $photoName,
            'status' => 'Active',
            'salary' => $data['salary'] ?? null,
            'paid_leaves' => $data['paid_leaves'] ?? 1,
            'blood_group' => $data['blood_group'] ?? null,
        ]);

        return response()->json(['success' => true, 'employee' => $employee]);
    }

    public function updateEmployee(Request $request): JsonResponse
    {
        $empId = $request->id ?? $request->employee_id;
        $employee = Employee::where('employee_id', $empId)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'designation' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'shiftStart' => 'nullable',
            'shiftEnd' => 'nullable',
            'gender' => 'nullable|string',
            'age' => 'nullable|integer',
            'dob' => 'nullable|date',
            'join_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'paid_leaves' => 'nullable|integer|min:0',
            'blood_group' => 'nullable|string|max:10',
        ]);
        $branchId = $employee->branch_id;
        if (!empty($data['branch'])) {
            $branch = Branch::firstOrCreate(['name' => $data['branch']]);
            $branchId = $branch->id;
        }

        if (!empty($data['designation'])) {
            Designation::firstOrCreate(['title' => $data['designation']], ['department' => 'General']);
        }

        $photoName = $employee->photo;
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('employee_profile_pic'), $photoName);
        }

        $employee->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'designation' => $data['designation'],
            'branch_id' => $branchId,
            'shift_start' => $data['shiftStart'] ?? $employee->shift_start,
            'shift_end' => $data['shiftEnd'] ?? $employee->shift_end,
            'gender' => $data['gender'] ?? $employee->gender,
            'age' => $data['age'] ?? $employee->age,
            'dob' => $data['dob'] ?? $employee->dob,
            'join_date' => $data['join_date'] ?? $employee->join_date,
            'photo' => $photoName,
            'salary' => $data['salary'] ?? $employee->salary,
            'paid_leaves' => $data['paid_leaves'] ?? $employee->paid_leaves,
            'blood_group' => $data['blood_group'] ?? $employee->blood_group,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteEmployee(Request $request): JsonResponse
    {
        Employee::where('employee_id', $request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function updateEmployeeStatus(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'status' => 'required|in:Active,Deactivated',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $employee->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $employee->status]);
    }

    public function branches(): JsonResponse
    {
        $branches = Branch::withCount('employees')->get()->map(function ($b) {
            return [
                'id' => 'BR-' . str_pad($b->id, 2, '0', STR_PAD_LEFT),
                'name' => $b->name,
                'location' => $b->location ?? '',
                'manager' => $b->manager ?? '',
                'phone' => $b->phone ?? '',
                'employees' => $b->employees_count,
            ];
        });

        return response()->json($branches);
    }

    public function storeBranch(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $branch = Branch::create($data);

        return response()->json(['success' => true, 'id' => 'BR-' . str_pad($branch->id, 2, '0', STR_PAD_LEFT)]);
    }

    public function deleteBranch(Request $request): JsonResponse
    {
        $id = (int) str_replace('BR-', '', $request->id);
        Branch::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function attendance(Request $request): JsonResponse
    {
        $period = $request->period ?? 'daily';
        $employees = Employee::with('branch')->get();

        if ($period === 'daily') {
            $today = Carbon::today();
            if ($request->date) {
                $today = Carbon::parse($request->date)->startOfDay();
            }
            $isHoliday = Holiday::whereDate('date', $today)->exists();
            $records = Attendance::with('user')->whereDate('date', $today)->get();
            $result = $employees->map(function ($e) use ($records, $isHoliday, $today) {
                $att = $records->firstWhere('user_id', $e->id) ?? $records->firstWhere('employee_id', $e->employee_id);
                $status = $att ? ucfirst($att->status) : 'Absent';
                if ($isHoliday) $status = 'Holiday';
                return [
                    'date' => $att ? $att->date->format('Y-m-d') : $today->format('Y-m-d'),
                    'employee_id' => $e->employee_id,
                    'att_id' => $att?->id,
                    'name' => $e->name,
                    'designation' => $e->designation,
                    'in' => $att?->check_in ? Carbon::parse($att->check_in)->format('h:i A') : '--',
                    'out' => $att?->check_out ? Carbon::parse($att->check_out)->format('h:i A') : '--',
                    'inRaw' => $att?->check_in ? Carbon::parse($att->check_in)->format('H:i') : null,
                    'outRaw' => $att?->check_out ? Carbon::parse($att->check_out)->format('H:i') : null,
                    'hours' => $att && $att->check_in && $att->check_out ? Carbon::parse($att->check_in)->diffInHours(Carbon::parse($att->check_out)) . 'h' : '--',
                    'status' => $status,
                    'latitude' => $att?->latitude,
                    'longitude' => $att?->longitude,
                    'location_name' => $att?->location_name,
                ];
            });
        } else {
            $start = match ($period) {
                'weekly' => Carbon::now()->startOfWeek(),
                'monthly' => Carbon::now()->startOfMonth(),
                'yearly' => Carbon::now()->startOfYear(),
                default => Carbon::now()->startOfWeek(),
            };
            $end = match ($period) {
                'weekly' => Carbon::now()->endOfWeek(),
                'monthly' => Carbon::now()->endOfMonth(),
                'yearly' => Carbon::now()->endOfYear(),
                default => Carbon::now()->endOfWeek(),
            };

            $records = Attendance::whereBetween('date', [$start, $end])->get();

            $result = $employees->map(function ($e) use ($records) {
                $empRecords = $records->filter(function ($r) use ($e) {
                    return $r->user_id === $e->id || $r->employee_id === $e->employee_id;
                });
                return [
                    'name' => $e->name,
                    'designation' => $e->designation,
                    'present' => $empRecords->where('status', 'present')->count(),
                    'absent' => $empRecords->where('status', 'absent')->count(),
                    'leave' => $empRecords->where('status', 'half-day')->count(),
                    'late' => $empRecords->where('status', 'late')->count(),
                    'totalHours' => $empRecords->sum(function ($a) {
                        return $a->check_in && $a->check_out
                            ? Carbon::parse($a->check_in)->diffInHours(Carbon::parse($a->check_out))
                            : 0;
                    }),
                ];
            });
        }

        return response()->json($result);
    }

    public function updateAttendance(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'date' => 'required|date',
            'field' => 'required|in:check_in,check_out',
            'time' => 'required|date_format:H:i',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $date = Carbon::parse($request->date)->toDateString();

        $attendance = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('date', $date)
            ->first();

        if ($request->field === 'check_in') {
            $checkIn = Carbon::parse($date . ' ' . $request->time);
            $checkOut = $attendance?->check_out ? Carbon::parse($attendance->check_out) : null;
            if ($checkOut && $checkOut->lt($checkIn)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Check-in cannot be after check-out (' . $checkOut->format('h:i A') . ').',
                ], 422);
            }
        } else {
            if (!$attendance || !$attendance->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Set a check-in first before adding a check-out.',
                ], 422);
            }
            $checkOut = Carbon::parse($date . ' ' . $request->time);
            $checkIn = Carbon::parse($attendance->check_in);
            if ($checkOut->lt($checkIn)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Check-out cannot be before check-in (' . $checkIn->format('h:i A') . ').',
                ], 422);
            }
        }

        $status = $this->attendanceStatus($checkIn, $checkOut);

        $user = User::where('email', $employee->email)->first();

        Attendance::updateOrCreate(
            ['employee_id' => $employee->employee_id, 'date' => $date],
            [
                'user_id' => $user?->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'notes' => 'Manually updated by admin',
            ]
        );

        $label = $request->field === 'check_in' ? 'Check-in' : 'Check-out';
        $time = $request->field === 'check_in' ? $checkIn : $checkOut;

        return response()->json([
            'success' => true,
            'message' => $label . ' updated for ' . $employee->name . ' on ' . $date
                . ' (' . $time->format('h:i A') . ').',
        ]);
    }

    private function attendanceStatus(Carbon $checkIn, ?Carbon $checkOut): string
    {
        if ($checkIn->format('H:i') >= '12:00') {
            return 'half-day';
        }
        if ($checkOut) {
            if ($checkOut->format('H:i') < '13:30') {
                return 'absent';
            }
            if ($checkOut->format('H:i') < self::SHIFT_END) {
                return 'half-day';
            }
        }
        return 'present';
    }

    public function leaveRequests(): JsonResponse
    {
        $leaves = LeaveRequest::with('user')->latest()->get()->map(function ($l) {
            $name = $l->user?->name;
            if (!$name && $l->employee_id) {
                $name = Employee::where('employee_id', $l->employee_id)->value('name');
            }
            return [
                'id' => $l->id,
                'name' => $name ?? 'Unknown',
                'type' => $l->type,
                'from' => $l->from_date->format('Y-m-d'),
                'to' => $l->to_date->format('Y-m-d'),
                'reason' => $l->reason ?? '',
                'status' => $l->status,
            ];
        });

        return response()->json($leaves);
    }

    public function leaveAction(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:leave_requests,id',
            'action' => 'required|in:Approved,Rejected',
        ]);

        $leave = LeaveRequest::with('user')->findOrFail($request->id);
        $leave->update(['status' => $request->action]);

        if ($request->action === 'Approved') {
            $employee = $leave->employee_id
                ? Employee::where('employee_id', $leave->employee_id)->first()
                : null;
            if (!$employee && $leave->user) {
                $employee = Employee::where('email', $leave->user->email)->first();
            }
            if ($employee) {
                $start = Carbon::parse($leave->from_date);
                $end = Carbon::parse($leave->to_date);
                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    Attendance::updateOrCreate(
                        ['employee_id' => $employee->employee_id, 'date' => $date->toDateString()],
                        ['status' => 'On Leave', 'notes' => $leave->type]
                    );
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function designations(): JsonResponse
    {
        $designations = Designation::all()->map(function ($d) {
            return [
                'title' => $d->title,
                'department' => $d->department,
                'count' => Employee::where('designation', $d->title)->count(),
            ];
        });

        return response()->json($designations);
    }

    public function storeDesignation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        Designation::create($data);

        return response()->json(['success' => true]);
    }

    public function notifications(): JsonResponse
    {
        $notifications = Notification::latest()->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'body' => $n->body ?? '',
                'time' => $n->created_at->diffForHumans(),
                'icon' => $n->type ?? 'bi-bell-fill',
                'color' => 'indigo',
                'unread' => !$n->is_read,
            ];
        });

        return response()->json($notifications);
    }

    public function markRead(Request $request): JsonResponse
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function sendNotification(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'target' => 'required|string|max:20',
        ]);

        $title = $request->title;
        $body = $request->body;

        if ($request->target === 'all') {
            $employees = Employee::all();
            foreach ($employees as $employee) {
                $user = User::where('email', $employee->email)->first();
                Notification::create([
                    'user_id' => $user?->id,
                    'employee_id' => $employee->employee_id,
                    'title' => $title,
                    'body' => $body,
                    'type' => 'bi-megaphone-fill',
                    'is_read' => false,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to all employees (' . $employees->count() . ').',
                'count' => $employees->count(),
            ]);
        }

        $employee = Employee::where('employee_id', $request->target)->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        $user = User::where('email', $employee->email)->first();
        Notification::create([
            'user_id' => $user?->id,
            'employee_id' => $employee->employee_id,
            'title' => $title,
            'body' => $body,
            'type' => 'bi-megaphone-fill',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification sent to ' . $employee->name . '.',
        ]);
    }

    public function salaryCalculations(): JsonResponse
    {
        $records = SalaryCalculation::with('employee')->latest()->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'employee_id' => $s->employee->employee_id,
                'employee_name' => $s->employee->name,
                'employee_img' => $s->employee->photo
                    ? asset('employee_profile_pic/' . $s->employee->photo)
                    : 'https://i.pravatar.cc/64?img=' . ($s->employee->id % 60),
                'base_salary' => $s->base_salary,
                'absent_days' => $s->absent_days,
                'leave_days' => $s->leave_days,
                'paid_leaves_used' => $s->paid_leaves_used,
                'deductible_days' => $s->deductible_days,
                'month' => $s->month,
                'final_salary' => $s->final_salary,
                'created_at' => $s->created_at->toDateString(),
            ];
        });

        return response()->json($records);
    }

    private function normalizeMonth(string $month): string
    {
        if (preg_match('/^\d{4}-\d{2}$/', $month)) {
            return $month;
        }

        $date = Carbon::createFromFormat('F-Y', $month);

        return $date ? $date->format('Y-m') : $month;
    }

    public function salaryPreview(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'month' => 'required|string|max:16',
        ]);

        $data['month'] = $this->normalizeMonth($data['month']);

        $employee = Employee::where('employee_id', $data['employee_id'])->firstOrFail();
        $baseSalary = $employee->salary ?? 0;

        [$year, $monthNum] = explode('-', $data['month']);
        $monthStart = Carbon::create((int)$year, (int)$monthNum, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $attendances = Attendance::where(function ($q) use ($employee) {
                $q->where('user_id', $employee->id)->orWhere('employee_id', $employee->employee_id);
            })
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $halfDays = $attendances->where('status', 'half-day')->count();
        $absentDays = $attendances->where('status', 'absent')->count();

        $approvedLeaves = LeaveRequest::where(function ($q) use ($employee) {
                $q->where('user_id', $employee->id)->orWhere('employee_id', $employee->employee_id);
            })
            ->where('status', 'Approved')
            ->whereBetween('from_date', [$monthStart, $monthEnd])
            ->get();

        $leaveDays = 0;
        foreach ($approvedLeaves as $leave) {
            $from = $leave->from_date instanceof Carbon ? $leave->from_date : Carbon::parse($leave->from_date);
            $to = $leave->to_date instanceof Carbon ? $leave->to_date : Carbon::parse($leave->to_date);
            if ($from->lte($monthEnd) && $to->gte($monthStart)) {
                $clampedFrom = $from->lt($monthStart) ? $monthStart->copy() : $from;
                $clampedTo = $to->gt($monthEnd) ? $monthEnd->copy() : $to;
                $leaveDays += $clampedFrom->diffInDays($clampedTo) + 1;
            }
        }

        $paidLeavesUsed = $employee->paid_leaves ?? 1;

        $joinDate = $employee->join_date ? Carbon::parse($employee->join_date) : null;
        if ($joinDate && $joinDate->gt($monthStart)) {
            $eligibleDays = min(30, (int)$joinDate->copy()->startOfDay()->diffInDays($monthEnd->copy()->startOfDay()) + 1);
        } else {
            $eligibleDays = 30;
        }

        $workedDays = $presentDays + ($halfDays * 0.5) + $leaveDays;
        $workedDays = min($workedDays, $eligibleDays);
        $deductibleDays = max(0, $eligibleDays - $workedDays);
        $perDay = $baseSalary / 30;
        $finalSalary = max(0, round($perDay * $workedDays, 2));

        return response()->json([
            'success' => true,
            'base_salary' => $baseSalary,
            'present_days' => $presentDays,
            'half_days' => $halfDays,
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'paid_leaves_used' => $paidLeavesUsed,
            'eligible_days' => $eligibleDays,
            'worked_days' => $workedDays,
            'deductible_days' => $deductibleDays,
            'final_salary' => $finalSalary,
        ]);
    }

    public function storeSalaryCalculation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'base_salary' => 'nullable|numeric|min:0',
            'absent_days' => 'nullable|integer|min:0',
            'leave_days' => 'nullable|integer|min:0',
            'month' => 'required|string|max:16',
        ]);

        $data['month'] = $this->normalizeMonth($data['month']);

        $employee = Employee::where('employee_id', $data['employee_id'])->firstOrFail();

        $baseSalary = $data['base_salary'] ?? $employee->salary;
        if (!$baseSalary) {
            return response()->json(['success' => false, 'message' => 'Employee has no base salary set.'], 422);
        }

        [$year, $monthNum] = explode('-', $data['month']);
        $monthStart = Carbon::create((int)$year, (int)$monthNum, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $attendances = Attendance::where(function ($q) use ($employee) {
                $q->where('user_id', $employee->id)->orWhere('employee_id', $employee->employee_id);
            })
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $halfDays = $attendances->where('status', 'half-day')->count();
        $absentDays = $data['absent_days'] ?? $attendances->where('status', 'absent')->count();

        $approvedLeaves = LeaveRequest::where(function ($q) use ($employee) {
                $q->where('user_id', $employee->id)->orWhere('employee_id', $employee->employee_id);
            })
            ->where('status', 'Approved')
            ->whereBetween('from_date', [$monthStart, $monthEnd])
            ->get();

        $leaveDays = $data['leave_days'] ?? 0;
        foreach ($approvedLeaves as $leave) {
            $from = $leave->from_date instanceof Carbon ? $leave->from_date : Carbon::parse($leave->from_date);
            $to = $leave->to_date instanceof Carbon ? $leave->to_date : Carbon::parse($leave->to_date);
            if ($from->lte($monthEnd) && $to->gte($monthStart)) {
                $clampedFrom = $from->lt($monthStart) ? $monthStart->copy() : $from;
                $clampedTo = $to->gt($monthEnd) ? $monthEnd->copy() : $to;
                $leaveDays += $clampedFrom->diffInDays($clampedTo) + 1;
            }
        }

        $paidLeavesUsed = $employee->paid_leaves ?? 1;

        $joinDate = $employee->join_date ? Carbon::parse($employee->join_date) : null;
        if ($joinDate && $joinDate->gt($monthStart)) {
            $eligibleDays = min(30, (int)$joinDate->copy()->startOfDay()->diffInDays($monthEnd->copy()->startOfDay()) + 1);
        } else {
            $eligibleDays = 30;
        }

        $workedDays = $presentDays + ($halfDays * 0.5) + $leaveDays;
        $workedDays = min($workedDays, $eligibleDays);
        $deductibleDays = max(0, $eligibleDays - $workedDays);
        $perDay = $baseSalary / 30;
        $finalSalary = max(0, round($perDay * $workedDays, 2));

        $monthKey = Carbon::create((int)$year, (int)$monthNum, 1)->format('F-Y');

        $existing = SalaryCalculation::where('employee_id', $employee->id)
            ->where(function ($q) use ($data, $monthKey) {
                $q->where('month', $data['month'])->orWhere('month', $monthKey);
            })
            ->first();

        $payload = [
            'processed_by' => auth()->id(),
            'base_salary' => $baseSalary,
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'paid_leaves_used' => $paidLeavesUsed,
            'deductible_days' => $deductibleDays,
            'final_salary' => $finalSalary,
        ];

        if ($existing) {
            $existing->update($payload);
            $record = $existing;
        } else {
            $record = SalaryCalculation::create(array_merge($payload, [
                'employee_id' => $employee->id,
                'month' => $monthKey,
            ]));
        }

        return response()->json(['success' => true, 'record' => $record]);
    }

    public function holidays(): JsonResponse
    {
        $holidays = Holiday::latest('date')->get()->map(function ($h) {
            return [
                'id' => $h->id,
                'date' => $h->date->toDateString(),
                'title' => $h->title,
            ];
        });

        return response()->json($holidays);
    }

    public function storeHoliday(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'title' => 'required|string|max:255',
        ]);

        Holiday::create($data);

        return response()->json(['success' => true]);
    }

    public function deleteHoliday(Request $request): JsonResponse
    {
        Holiday::where('id', $request->id)->delete();
        return response()->json(['success' => true]);
    }
}
