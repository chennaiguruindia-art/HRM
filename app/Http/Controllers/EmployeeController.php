<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function login()
    {
        return view('employee.login');
    }

    public function lookup(Request $request): JsonResponse
    {
        $request->validate(['employee_id' => 'required|string']);

        $employee = Employee::with('branch')->where('employee_id', $request->employee_id)->first();

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
}
