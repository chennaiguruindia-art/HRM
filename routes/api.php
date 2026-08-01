<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Mobile app endpoints (JSON). Prefix: /api
|
| NOTE: Laravel Sanctum is not installed yet, so these routes are open.
| Add ->middleware('auth:sanctum') once token auth is set up.
*/

Route::prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/dashboard-stats', [App\Http\Controllers\Admin\ApiController::class, 'dashboardStats'])->name('dashboard-stats');
    Route::get('/employees', [App\Http\Controllers\Admin\ApiController::class, 'employees'])->name('employees');
    Route::post('/employees', [App\Http\Controllers\Admin\ApiController::class, 'storeEmployee'])->name('employees.store');
    Route::post('/employees/update', [App\Http\Controllers\Admin\ApiController::class, 'updateEmployee'])->name('employees.update');
    Route::post('/employees/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteEmployee'])->name('employees.delete');
    Route::post('/employees/status', [App\Http\Controllers\Admin\ApiController::class, 'updateEmployeeStatus'])->name('employees.status');
    Route::get('/branches', [App\Http\Controllers\Admin\ApiController::class, 'branches'])->name('branches');
    Route::post('/branches', [App\Http\Controllers\Admin\ApiController::class, 'storeBranch'])->name('branches.store');
    Route::post('/branches/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteBranch'])->name('branches.delete');
    Route::get('/attendance', [App\Http\Controllers\Admin\ApiController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/update', [App\Http\Controllers\Admin\ApiController::class, 'updateAttendance'])->name('attendance.update');
    Route::get('/leave-requests', [App\Http\Controllers\Admin\ApiController::class, 'leaveRequests'])->name('leave-requests');
    Route::post('/leave-action', [App\Http\Controllers\Admin\ApiController::class, 'leaveAction'])->name('leave-action');
    Route::get('/designations', [App\Http\Controllers\Admin\ApiController::class, 'designations'])->name('designations');
    Route::post('/designations', [App\Http\Controllers\Admin\ApiController::class, 'storeDesignation'])->name('designations.store');
    Route::get('/notifications', [App\Http\Controllers\Admin\ApiController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/read', [App\Http\Controllers\Admin\ApiController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/send', [App\Http\Controllers\Admin\ApiController::class, 'sendNotification'])->name('notifications.send');
    Route::get('/salary-calculations', [App\Http\Controllers\Admin\ApiController::class, 'salaryCalculations'])->name('salary-calculations');
    Route::get('/salary-preview', [App\Http\Controllers\Admin\ApiController::class, 'salaryPreview'])->name('salary-preview');
    Route::post('/salary-calculations', [App\Http\Controllers\Admin\ApiController::class, 'storeSalaryCalculation'])->name('salary-calculations.store');
    Route::get('/holidays', [App\Http\Controllers\Admin\ApiController::class, 'holidays'])->name('holidays');
    Route::post('/holidays', [App\Http\Controllers\Admin\ApiController::class, 'storeHoliday'])->name('holidays.store');
    Route::post('/holidays/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteHoliday'])->name('holidays.delete');
});

Route::prefix('employee')->name('api.employee.')->group(function () {
    Route::post('/lookup', [App\Http\Controllers\EmployeeController::class, 'lookup'])->name('lookup');
    Route::get('/dashboard/{employee_id}', [App\Http\Controllers\EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [App\Http\Controllers\EmployeeController::class, 'logout'])->name('logout');
    Route::post('/clock-in', [App\Http\Controllers\EmployeeController::class, 'clockIn'])->name('clock-in');
    Route::post('/clock-out', [App\Http\Controllers\EmployeeController::class, 'clockOut'])->name('clock-out');
    Route::post('/leave', [App\Http\Controllers\EmployeeController::class, 'storeLeaveRequest'])->name('leave');
    Route::post('/profile/update', [App\Http\Controllers\EmployeeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/notifications/read', [App\Http\Controllers\EmployeeController::class, 'markNotificationsRead'])->name('notifications.read');
    Route::post('/address-from-coords', [App\Http\Controllers\EmployeeController::class, 'addressFromCoords'])->name('address-from-coords');
});
