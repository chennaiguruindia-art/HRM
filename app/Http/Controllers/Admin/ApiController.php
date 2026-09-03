<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\DailyPlan;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\OldData;
use App\Models\SalaryCalculation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Services\EmployeeNotificationService;
use App\Services\SimpleXlsxReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ApiController extends Controller
{
    const SHIFT_START = '09:30';
    const SHIFT_END = '18:30';

    private function branchScope(): ?int
    {
        $user = Auth::user();

        return $user && $user->branch_id ? (int) $user->branch_id : null;
    }

    private function branchEmployeeIds(): ?array
    {
        $branchId = $this->branchScope();

        if (!$branchId) {
            return null;
        }

        return Employee::where('branch_id', $branchId)->pluck('employee_id')->all();
    }

    private function branchEmployeeModelIds(): ?array
    {
        $branchId = $this->branchScope();

        if (!$branchId) {
            return null;
        }

        return Employee::where('branch_id', $branchId)->pluck('id')->all();
    }

    private function ensureBranchAccess(Employee $employee): void
    {
        $branchId = $this->branchScope();

        if ($branchId && (int) $employee->branch_id !== $branchId) {
            abort(403, 'You can only manage employees in your branch.');
        }
    }

    private function denyBranchAdmin(): void
    {
        if ($this->branchScope()) {
            abort(403, 'Branch admins are not allowed to perform this action.');
        }
    }

    public function runMigrations()
    {
        Artisan::call('migrate', ['--force' => true]);

        return '<pre>' . Artisan::output() . '</pre>';
    }

    public function runMigrationsFresh()
    {
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);

        return '<pre>' . Artisan::output() . '</pre>';
    }

    public function runSeeders()
    {
        Artisan::call('db:seed', ['--force' => true]);

        return '<pre>' . Artisan::output() . '</pre>';
    }

    public function dashboardStats(): JsonResponse
    {
        Attendance::processAutoClockOuts();
        EmployeeNotificationService::checkDailyAnniversariesAndBirthdays();

        $branchId = $this->branchScope();
        $empIds = $this->branchEmployeeIds();

        $total = Employee::query()->when($branchId, fn ($q) => $q->where('branch_id', $branchId))->count();
        $today = Carbon::today();
        $presentToday = Attendance::query()->whereRaw('DATE(date) = ?', [$today->toDateString()])->whereIn('status', ['present', 'half-day', 'late'])
            ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
            ->count();
        $onLeave = LeaveRequest::query()->where('status', 'Approved')
            ->where('from_date', '<=', $today->toDateString())
            ->where('to_date', '>=', $today->toDateString())
            ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
            ->count();
        $pending = LeaveRequest::where('status', 'Pending')
            ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
            ->count();
        $absent = max(0, $total - $presentToday - $onLeave);

        return response()->json([
            'total' => $total ?: 0,
            'present' => $presentToday ?: 0,
            'onLeave' => $onLeave ?: 0,
            'absent' => $absent,
            'pending' => $pending ?: 0,
        ]);
    }

    public function employees(): JsonResponse
    {
        $branchId = $this->branchScope();
        $employees = Employee::with('branch')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get()->map(function ($e) {
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

        $scopedBranchId = $this->branchScope();
        if ($scopedBranchId) {
            $branchId = $scopedBranchId;
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
        $this->ensureBranchAccess($employee);

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

        $scopedBranchId = $this->branchScope();
        if ($scopedBranchId) {
            $branchId = $scopedBranchId;
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
        $employee = Employee::where('employee_id', $request->id)->firstOrFail();
        $this->ensureBranchAccess($employee);
        $employee->delete();
        return response()->json(['success' => true]);
    }

    public function updateEmployeeStatus(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'status' => 'required|in:Active,Deactivated',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $this->ensureBranchAccess($employee);
        $employee->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $employee->status]);
    }

    public function branches(): JsonResponse
    {
        $branchId = $this->branchScope();
        $branches = Branch::withCount('employees')
            ->when($branchId, fn ($q) => $q->where('id', $branchId))
            ->get()->map(function ($b) {
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
        $this->denyBranchAdmin();

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
        $this->denyBranchAdmin();

        $id = (int) str_replace('BR-', '', $request->id);
        Branch::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function attendance(Request $request): JsonResponse
    {
        $period = $request->period ?? 'daily';
        $branchId = $this->branchScope();
        $empIds = $this->branchEmployeeIds();
        $employees = Employee::with('branch')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get();

        if ($period === 'daily') {
            $today = Carbon::today();
            if ($request->date) {
                $today = Carbon::parse($request->date)->startOfDay();
            }
            $isHoliday = Holiday::query()->whereRaw('DATE(date) = ?', [$today->toDateString()])->exists();
            $records = Attendance::query()->with('user')
                ->whereRaw('DATE(date) = ?', [$today->toDateString()])
                ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
                ->get();
            $result = $employees->map(function ($e) use ($records, $isHoliday, $today) {
                $att = $records->firstWhere('user_id', $e->id) ?? $records->firstWhere('employee_id', $e->employee_id);
                $status = $att ? ucfirst($att->status) : 'Absent';
                if ($isHoliday) $status = 'Holiday';
                return [
                    'date' => $att && $att->date ? Carbon::parse($att->date)->format('Y-m-d') : $today->format('Y-m-d'),
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
            'edited_lat' => 'nullable|numeric',
            'edited_lng' => 'nullable|numeric',
            'edited_location_name' => 'nullable|string|max:255',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $this->ensureBranchAccess($employee);
        $date = Carbon::parse($request->date)->toDateString();

        $attendance = Attendance::query()->where('employee_id', $employee->employee_id)
            ->whereRaw('DATE(date) = ?', [$date])
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
                'edited_lat' => $request->edited_lat,
                'edited_lng' => $request->edited_lng,
                'edited_location_name' => $request->edited_location_name,
                'edited_by' => Auth::id(),
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

    public function dailyReports(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'date' => 'nullable|date',
            'month' => 'nullable|date_format:Y-m',
        ]);

        $query = Attendance::where('employee_id', $request->employee_id);
        $employee = Employee::where('employee_id', $request->employee_id)->firstOrFail();
        $this->ensureBranchAccess($employee);

        if ($request->date) {
            $query->whereRaw('DATE(date) = ?', [Carbon::parse($request->date)->toDateString()]);
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

    private function attendanceStatus(Carbon $checkIn, ?Carbon $checkOut): string
    {
        return Attendance::calculateStatus($checkIn, $checkOut);
    }

    public function leaveRequests(): JsonResponse
    {
        $empIds = $this->branchEmployeeIds();
        $leaves = LeaveRequest::with('user')
            ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
            ->latest()->get()->map(function ($l) {
            $name = $l->user?->name;
            if (!$name && $l->employee_id) {
                $name = Employee::where('employee_id', $l->employee_id)->value('name');
            }
            return [
                'id' => $l->id,
                'name' => $name ?? 'Unknown',
                'type' => $l->type,
                'hours' => $l->hours,
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
        if ($leave->employee_id) {
            $leaveEmployee = Employee::where('employee_id', $leave->employee_id)->first();
            if ($leaveEmployee) {
                $this->ensureBranchAccess($leaveEmployee);
            }
        }
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
                    if (strtolower($leave->type) === 'permission') {
                        Attendance::updateOrCreate(
                            ['employee_id' => $employee->employee_id, 'date' => $date->toDateString()],
                            ['notes' => 'Permission (' . ($leave->hours ?? 1) . ' hr)']
                        );
                    } elseif (stripos($leave->type, 'Half Day') !== false) {
                        Attendance::updateOrCreate(
                            ['employee_id' => $employee->employee_id, 'date' => $date->toDateString()],
                            ['status' => 'half-day', 'notes' => $leave->type]
                        );
                    } else {
                        Attendance::updateOrCreate(
                            ['employee_id' => $employee->employee_id, 'date' => $date->toDateString()],
                            ['status' => 'On Leave', 'notes' => $leave->type]
                        );
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function designations(): JsonResponse
    {
        $branchId = $this->branchScope();
        $designations = Designation::all()->map(function ($d) use ($branchId) {
            return [
                'id' => $d->id,
                'title' => $d->title,
                'department' => $d->department,
                'count' => Employee::where('designation', $d->title)
                    ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                    ->count(),
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

        Designation::create([
            'title' => trim($data['title']),
            'department' => trim($data['department']),
        ]);

        return response()->json(['success' => true]);
    }

    public function updateDesignation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:designations,id',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $designation = Designation::findOrFail($data['id']);
        $oldTitle = $designation->title;
        $newTitle = trim($data['title']);

        $designation->update([
            'title' => $newTitle,
            'department' => trim($data['department']),
        ]);

        if ($oldTitle !== $newTitle) {
            Employee::where('designation', $oldTitle)
                ->update(['designation' => $newTitle]);
        }

        return response()->json(['success' => true]);
    }

    public function deleteDesignation(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:designations,id',
        ]);

        Designation::destroy($request->id);

        return response()->json(['success' => true]);
    }

    public function notifications(): JsonResponse
    {
        EmployeeNotificationService::checkDailyAnniversariesAndBirthdays();

        $empIds = $this->branchEmployeeIds();
        $notifications = Notification::query()
            ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
            ->latest()->get()->map(function ($n) {
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
        $empIds = $this->branchEmployeeIds();
        Notification::where('is_read', false)
            ->when($empIds !== null, fn ($q) => $q->whereIn('employee_id', $empIds))
            ->update(['is_read' => true]);
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
        $branchId = $this->branchScope();

        if ($request->target === 'all') {
            $employees = Employee::query()
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                ->get();
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
        $this->ensureBranchAccess($employee);

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
        $branchEmpIds = $this->branchEmployeeModelIds();
        $records = SalaryCalculation::with('employee')
            ->when($branchEmpIds !== null, fn ($q) => $q->whereIn('employee_id', $branchEmpIds))
            ->latest()->get()->map(function ($s) {
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
        $this->ensureBranchAccess($employee);
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
        $daysInMonth = $monthStart->daysInMonth;

        $joinDate = $employee->join_date ? Carbon::parse($employee->join_date) : null;
        if ($joinDate && $joinDate->gt($monthStart)) {
            $eligibleDays = min($daysInMonth, (int)$joinDate->copy()->startOfDay()->diffInDays($monthEnd->copy()->startOfDay()) + 1);
        } else {
            $eligibleDays = $daysInMonth;
        }

        $workedDays = $presentDays + ($halfDays * 0.5) + $leaveDays;
        $workedDays = min($workedDays, $eligibleDays);
        $deductibleDays = max(0, $eligibleDays - $workedDays);
        $perDay = $baseSalary / $daysInMonth;
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
        $this->ensureBranchAccess($employee);

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
        $daysInMonth = $monthStart->daysInMonth;

        $joinDate = $employee->join_date ? Carbon::parse($employee->join_date) : null;
        if ($joinDate && $joinDate->gt($monthStart)) {
            $eligibleDays = min($daysInMonth, (int)$joinDate->copy()->startOfDay()->diffInDays($monthEnd->copy()->startOfDay()) + 1);
        } else {
            $eligibleDays = $daysInMonth;
        }

        $workedDays = $presentDays + ($halfDays * 0.5) + $leaveDays;
        $workedDays = min($workedDays, $eligibleDays);
        $deductibleDays = max(0, $eligibleDays - $workedDays);
        $perDay = $baseSalary / $daysInMonth;
        $finalSalary = max(0, round($perDay * $workedDays, 2));

        $monthKey = Carbon::create((int)$year, (int)$monthNum, 1)->format('F-Y');

        $existing = SalaryCalculation::where('employee_id', $employee->id)
            ->where(function ($q) use ($data, $monthKey) {
                $q->where('month', $data['month'])->orWhere('month', $monthKey);
            })
            ->first();

        $payload = [
            'processed_by' => Auth::id(),
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
        $branchId = $this->branchScope();

        $holidays = Holiday::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest('date')
            ->get()
            ->map(function ($h) {
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
        $branchId = $this->branchScope();

        $data = $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
        ]);

        $data['branch_id'] = $branchId;

        Holiday::create($data);

        return response()->json(['success' => true]);
    }

    public function deleteHoliday(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $query = Holiday::where('id', $request->id);
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        $query->delete();

        return response()->json(['success' => true]);
    }

    public function dailyPlans(): JsonResponse
    {
        $branchId = $this->branchScope();

        $plans = DailyPlan::with('branch')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->latest('date')
            ->latest('id')
            ->get()
            ->map(function ($p, $i) {
                return [
                    'sino' => $i + 1,
                    'id' => $p->id,
                    'branch' => $p->branch?->name ?? '-',
                    'date' => $p->date->toDateString(),
                    'salesperson' => $p->salesperson,
                    'company_address' => $p->company_address,
                    'company_details' => $p->company_details,
                    'purpose_of_visit' => $p->purpose_of_visit,
                    'type_of_service' => $p->type_of_service,
                    'inspection' => $p->inspection,
                    'quotation' => $p->quotation,
                    'followup1' => $p->followup1,
                    'followup2' => $p->followup2,
                    'followup3' => $p->followup3,
                    'remarks' => $p->remarks,
                    'updated_at' => $p->updated_at->format('Y-m-d H:i'),
                ];
            });

        return response()->json($plans);
    }

    public function storeDailyPlan(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $data = $request->validate([
            'date' => 'required|date',
            'salesperson' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_details' => 'nullable|string',
            'purpose_of_visit' => 'nullable|string',
            'type_of_service' => 'nullable|string',
            'inspection' => 'nullable|string',
            'quotation' => 'nullable|string',
            'followup1' => 'nullable|string',
            'followup2' => 'nullable|string',
            'followup3' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $data['branch_id'] = $branchId;

        DailyPlan::create($data);

        return response()->json(['success' => true]);
    }

    public function updateDailyPlan(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $plan = DailyPlan::findOrFail($request->id);

        if ($branchId && (int) $plan->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $data = $request->validate([
            'date' => 'required|date',
            'salesperson' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_details' => 'nullable|string',
            'purpose_of_visit' => 'nullable|string',
            'type_of_service' => 'nullable|string',
            'inspection' => 'nullable|string',
            'quotation' => 'nullable|string',
            'followup1' => 'nullable|string',
            'followup2' => 'nullable|string',
            'followup3' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $plan->update($data);

        return response()->json(['success' => true]);
    }

    public function deleteDailyPlan(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $plan = DailyPlan::findOrFail($request->id);

        if ($branchId && (int) $plan->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $plan->delete();

        return response()->json(['success' => true]);
    }

    public function adminList(): JsonResponse
    {
        $admins = User::where('role', 'admin')
            ->with('branch')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'branch' => $u->branch?->name ?? 'Super Admin',
                ];
            });

        return response()->json($admins);
    }

    public function adminNotifications(): JsonResponse
    {
        $userId = Auth::id();

        $notes = AdminNotification::where('to_user_id', $userId)
            ->latest()
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'from' => $n->fromUser->name ?? 'System',
                    'title' => $n->title,
                    'body' => $n->body,
                    'time' => $n->created_at->diffForHumans(),
                    'unread' => $n->read_at === null,
                ];
            });

        return response()->json($notes);
    }

    public function sendAdminNotification(Request $request): JsonResponse
    {
        $fromUserId = Auth::id();

        $data = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        AdminNotification::create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $data['to_user_id'],
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
        ]);

        return response()->json(['success' => true]);
    }

    public function markAdminNotificationsRead(): JsonResponse
    {
        $userId = Auth::id();

        AdminNotification::where('to_user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /* =========================================================================
       OLD DATA / EXCEL IMPORT
       ========================================================================= */

    public function oldData(Request $request): JsonResponse
    {
        $branchId = $this->branchScope() ?? ($request->filled('branch_id') ? (int) $request->branch_id : null);
        $search = trim((string) $request->input('search', $request->input('q', '')));
        $dateFilter = trim((string) $request->input('date', ''));

        $query = OldData::with('branch')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($dateFilter, fn($q) => $q->where('entry_date', 'like', "%{$dateFilter}%"))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('staff_name', 'like', "%{$search}%")
                        ->orWhere('work_name', 'like', "%{$search}%")
                        ->orWhere('units', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('sn', 'like', "%{$search}%")
                        ->orWhere('entry_date', 'like', "%{$search}%");
                });
            })
            ->latest('id');

        $records = $query->get()->map(function ($row, $index) {
            return [
                'sino' => $index + 1,
                'id' => $row->id,
                'branch_id' => $row->branch_id,
                'branch' => $row->branch?->name ?? '-',
                'sn' => $row->sn ?? '',
                'staff_name' => $row->staff_name ?? '',
                'entry_date' => $row->entry_date ?? '',
                'entry_time' => $row->entry_time ?? '',
                'work_name' => $row->work_name ?? '',
                'units' => $row->units ?? '',
                'description' => $row->description ?? '',
                'location' => $row->location ?? '',
                'created_at' => $row->created_at ? $row->created_at->format('d-m-Y H:i') : '-',
            ];
        });

        return response()->json($records);
    }

    public function uploadOldData(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:30720',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
            return response()->json(['success' => false, 'message' => 'Invalid file format. Please upload an Excel (.xlsx, .xls) or CSV file.'], 422);
        }

        $branchId = $this->branchScope() ?? ($request->filled('branch_id') ? (int) $request->branch_id : null);

        try {
            $rows = SimpleXlsxReader::read($file->getRealPath());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to read file: ' . $e->getMessage()], 422);
        }

        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'The uploaded file is empty.'], 422);
        }

        // Detect header row
        $headerRowIdx = null;
        $colMap = [
            'sn' => null,
            'staff_name' => null,
            'entry_date' => null,
            'entry_time' => null,
            'work_name' => null,
            'units' => null,
            'description' => null,
            'location' => null,
        ];

        foreach ($rows as $rIdx => $row) {
            $rowValues = array_map(fn($v) => strtolower(trim((string) $v)), $row);
            $joined = implode(' ', $rowValues);

            // Check if this row looks like header
            if (
                str_contains($joined, 'staff') ||
                str_contains($joined, 'work') ||
                str_contains($joined, 'unit') ||
                str_contains($joined, 's.n') ||
                str_contains($joined, 'sn')
            ) {
                $headerRowIdx = $rIdx;
                foreach ($row as $colLetter => $cellVal) {
                    $clean = strtolower(trim((string) $cellVal));
                    if (empty($clean)) continue;

                    if ($colMap['sn'] === null && (str_contains($clean, 's.n') || $clean === 'sn' || str_contains($clean, 's.no') || str_contains($clean, 'serial'))) {
                        $colMap['sn'] = $colLetter;
                    } elseif ($colMap['staff_name'] === null && (str_contains($clean, 'staff') || str_contains($clean, 'employee') || ($clean === 'name' && !str_contains($clean, 'work')))) {
                        $colMap['staff_name'] = $colLetter;
                    } elseif ($colMap['entry_date'] === null && str_contains($clean, 'date')) {
                        $colMap['entry_date'] = $colLetter;
                    } elseif ($colMap['entry_time'] === null && str_contains($clean, 'time')) {
                        $colMap['entry_time'] = $colLetter;
                    } elseif ($colMap['work_name'] === null && (str_contains($clean, 'work') || str_contains($clean, 'task'))) {
                        $colMap['work_name'] = $colLetter;
                    } elseif ($colMap['units'] === null && str_contains($clean, 'unit')) {
                        $colMap['units'] = $colLetter;
                    } elseif ($colMap['description'] === null && (str_contains($clean, 'desc') || str_contains($clean, 'remark') || str_contains($clean, 'detail'))) {
                        $colMap['description'] = $colLetter;
                    } elseif ($colMap['location'] === null && (str_contains($clean, 'loc') || str_contains($clean, 'site') || str_contains($clean, 'place') || str_contains($clean, 'address'))) {
                        $colMap['location'] = $colLetter;
                    }
                }
                break;
            }
        }

        // Fallback positional map if header row wasn't fully detected
        $startRow = $headerRowIdx ? $headerRowIdx + 1 : 1;
        if ($colMap['staff_name'] === null && $colMap['work_name'] === null) {
            // Default positional mapping: A=SN, B=Staff Name, C=Entry Date, D=Entry Time, E=Work Name, F=Units, G=Description, H=Location
            $colLetters = array_keys(reset($rows));
            $colMap['sn'] = $colLetters[0] ?? 'A';
            $colMap['staff_name'] = $colLetters[1] ?? 'B';
            $colMap['entry_date'] = $colLetters[2] ?? 'C';
            $colMap['entry_time'] = $colLetters[3] ?? 'D';
            $colMap['work_name'] = $colLetters[4] ?? 'E';
            $colMap['units'] = $colLetters[5] ?? 'F';
            $colMap['description'] = $colLetters[6] ?? 'G';
            $colMap['location'] = $colLetters[7] ?? 'H';
            $startRow = 2; // Skip first row as assumed header
        }

        $imported = 0;
        $batch = [];

        foreach ($rows as $rIdx => $row) {
            if ($rIdx < $startRow) {
                continue;
            }

            $snVal = isset($colMap['sn'], $row[$colMap['sn']]) ? trim((string) $row[$colMap['sn']]) : null;
            $staffVal = isset($colMap['staff_name'], $row[$colMap['staff_name']]) ? trim((string) $row[$colMap['staff_name']]) : null;
            $dateVal = isset($colMap['entry_date'], $row[$colMap['entry_date']]) ? trim((string) $row[$colMap['entry_date']]) : null;
            $timeVal = isset($colMap['entry_time'], $row[$colMap['entry_time']]) ? trim((string) $row[$colMap['entry_time']]) : null;
            $workVal = isset($colMap['work_name'], $row[$colMap['work_name']]) ? trim((string) $row[$colMap['work_name']]) : null;
            $unitsVal = isset($colMap['units'], $row[$colMap['units']]) ? trim((string) $row[$colMap['units']]) : null;
            $descVal = isset($colMap['description'], $row[$colMap['description']]) ? trim((string) $row[$colMap['description']]) : null;
            $locVal = isset($colMap['location'], $row[$colMap['location']]) ? trim((string) $row[$colMap['location']]) : null;

            // Skip empty rows
            if (empty($snVal) && empty($staffVal) && empty($dateVal) && empty($workVal) && empty($unitsVal) && empty($descVal) && empty($locVal)) {
                continue;
            }

            // Skip repeated header line if any
            if (strtolower($staffVal) === 'staff name' || strtolower($workVal) === 'work name') {
                continue;
            }

            $batch[] = [
                'branch_id' => $branchId,
                'sn' => $snVal ?: null,
                'staff_name' => $staffVal ?: null,
                'entry_date' => $dateVal ?: null,
                'entry_time' => $timeVal ?: null,
                'work_name' => $workVal ?: null,
                'units' => $unitsVal ?: null,
                'description' => $descVal ?: null,
                'location' => $locVal ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $imported++;

            if (count($batch) >= 500) {
                OldData::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            OldData::insert($batch);
        }

        return response()->json([
            'success' => true,
            'count' => $imported,
            'message' => "Successfully imported {$imported} record(s) from Excel file.",
        ]);
    }

    public function storeOldData(Request $request): JsonResponse
    {
        $branchId = $this->branchScope() ?? ($request->filled('branch_id') ? (int) $request->branch_id : null);

        $data = $request->validate([
            'sn' => 'nullable|string|max:50',
            'staff_name' => 'nullable|string|max:255',
            'entry_date' => 'nullable|string|max:100',
            'entry_time' => 'nullable|string|max:100',
            'work_name' => 'nullable|string',
            'units' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $data['branch_id'] = $branchId;

        $record = OldData::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Record added successfully.',
            'data' => $record,
        ]);
    }

    public function updateOldData(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $record = OldData::findOrFail($request->id);

        if ($branchId && (int) $record->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $data = $request->validate([
            'sn' => 'nullable|string|max:50',
            'staff_name' => 'nullable|string|max:255',
            'entry_date' => 'nullable|string|max:100',
            'entry_time' => 'nullable|string|max:100',
            'work_name' => 'nullable|string',
            'units' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if (!$branchId && $request->has('branch_id')) {
            $data['branch_id'] = $request->branch_id;
        }

        $record->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully.',
            'data' => $record,
        ]);
    }

    public function deleteOldData(Request $request): JsonResponse
    {
        $branchId = $this->branchScope();

        $record = OldData::findOrFail($request->id);

        if ($branchId && (int) $record->branch_id !== $branchId) {
            abort(403, 'Access denied.');
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.',
        ]);
    }

    public function clearOldData(Request $request): JsonResponse
    {
        $branchId = $this->branchScope() ?? ($request->filled('branch_id') ? (int) $request->branch_id : null);

        $query = OldData::query();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $deletedCount = $query->delete();

        return response()->json([
            'success' => true,
            'message' => "Cleared {$deletedCount} old data record(s).",
        ]);
    }

    public function sampleOldData()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="old_data_sample_template.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Header row matching screenshot + requested description and location columns
            fputcsv($handle, ['S.N.', 'Staff Name', 'Entry Date', 'Entry Time', 'Work Name', 'Units', 'Description', 'Location']);

            // Sample rows based on user's image
            fputcsv($handle, ['1', 'B RAHUL', '01-08-2026 | Saturday', '06:47 PM', 'Futura Tech Park - Sholinganallur', 'Prabhu Sir', 'Regular maintenance inspection', 'Futura Tech Park']);
            fputcsv($handle, ['2', 'B RAHUL', '03-08-2026 | Monday', '05:22 PM', 'Futura Tech Park - Sholinganallur', 'Prabhu Sir', 'Daily status review', 'Futura Tech Park']);
            fputcsv($handle, ['3', 'B RAHUL', '03-08-2026 | Monday', '12:41 PM', 'Futura Tech Park - Sholinganallur', 'Finding Man Power Sourcing to deploy', 'Manpower allocation discussion', 'Sholinganallur']);
            fputcsv($handle, ['4', 'B RAHUL', '04-08-2026 | Tuesday', '06:17 PM', 'Futura Tech Park - Sholinganallur', 'Prabhu Sir', 'Site coordination', 'Futura Tech Park']);
            fputcsv($handle, ['5', 'B RAHUL', '05-08-2026 | Wednesday', '05:11 PM', 'Futura Tech Park - Sholinganallur', 'Prabhu Sir', 'Team review', 'Futura Tech Park']);
            fputcsv($handle, ['6', 'B RAHUL', '06-08-2026 | Thursday', '06:35 PM', 'Prestige Silver Springs - Sholinganallur', 'Dhamodharan Sir', 'Facility supervision', 'Prestige Silver Springs']);
            fputcsv($handle, ['7', 'B RAHUL', '06-08-2026 | Thursday', '12:35 PM', 'Futura Tech Park - Sholinganallur', 'Prabhu Sir', 'Afternoon update', 'Futura Tech Park']);
            fputcsv($handle, ['8', 'B RAHUL', '07-08-2026 | Friday', '02:18 PM', 'LTM - Ramapuram', 'Kabin Supervisor', 'Weekly site inspection', 'LTM Ramapuram']);

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}

