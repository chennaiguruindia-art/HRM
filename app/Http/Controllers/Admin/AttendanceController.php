<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $attendances = Attendance::with('user')->latest()->paginate(10);
        return view('admin.attendance.index', compact('attendances'));
    }

    public function create(): View
    {
        $users = User::all();
        return view('admin.attendance.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'required|in:present,late,absent,half-day',
            'notes' => 'nullable|string|max:1000',
        ]);

        Attendance::create($validated);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record created successfully.');
    }

    public function edit(Attendance $attendance): View
    {
        $users = User::all();
        return view('admin.attendance.edit', compact('attendance', 'users'));
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'required|in:present,late,absent,half-day',
            'notes' => 'nullable|string|max:1000',
        ]);

        $attendance->update($validated);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record updated successfully.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record deleted successfully.');
    }
}
