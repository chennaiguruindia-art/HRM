<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('reports')->name('reports.')->middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\ReportsAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\ReportsAuthController::class, 'login']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/{slug}admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->where('slug', '[a-z]+')
    ->name('admin.branch-dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('api')->name('api.')->group(function () {
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
        Route::get('/reports/daily', [App\Http\Controllers\Admin\ApiController::class, 'dailyReports'])->name('reports.daily');
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
        Route::get('/daily-plans', [App\Http\Controllers\Admin\ApiController::class, 'dailyPlans'])->name('daily-plans');
        Route::post('/daily-plans', [App\Http\Controllers\Admin\ApiController::class, 'storeDailyPlan'])->name('daily-plans.store');
        Route::post('/daily-plans/update', [App\Http\Controllers\Admin\ApiController::class, 'updateDailyPlan'])->name('daily-plans.update');
        Route::post('/daily-plans/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteDailyPlan'])->name('daily-plans.delete');
        Route::get('/admin-list', [App\Http\Controllers\Admin\ApiController::class, 'adminList'])->name('admin-list');
        Route::get('/admin-notifications', [App\Http\Controllers\Admin\ApiController::class, 'adminNotifications'])->name('admin-notifications');
        Route::post('/admin-notifications/send', [App\Http\Controllers\Admin\ApiController::class, 'sendAdminNotification'])->name('admin-notifications.send');
        Route::post('/admin-notifications/read', [App\Http\Controllers\Admin\ApiController::class, 'markAdminNotificationsRead'])->name('admin-notifications.read');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login', [App\Http\Controllers\EmployeeController::class, 'login'])->name('login');
    Route::get('/dashboard/{employee_id}', [App\Http\Controllers\EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [App\Http\Controllers\EmployeeController::class, 'logout'])->name('logout');
    Route::get('/reports/daily', [App\Http\Controllers\EmployeeController::class, 'dailyReports'])->name('reports.daily');
    Route::post('/lookup', [App\Http\Controllers\EmployeeController::class, 'lookup'])->name('lookup');
    Route::post('/address-from-coords', [App\Http\Controllers\EmployeeController::class, 'addressFromCoords'])->name('address-from-coords');
    Route::post('/clock-in', [App\Http\Controllers\EmployeeController::class, 'clockIn'])->name('clock-in');
    Route::post('/clock-out', [App\Http\Controllers\EmployeeController::class, 'clockOut'])->name('clock-out');
    Route::post('/leave', [App\Http\Controllers\EmployeeController::class, 'storeLeaveRequest'])->name('leave');
    Route::post('/profile/update', [App\Http\Controllers\EmployeeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/notifications/read', [App\Http\Controllers\EmployeeController::class, 'markNotificationsRead'])->name('notifications.read');
    Route::get('/daily-work-updates', [App\Http\Controllers\EmployeeController::class, 'dailyWorkUpdates'])->name('daily-work-updates');
    Route::post('/daily-work-updates', [App\Http\Controllers\EmployeeController::class, 'storeDailyWorkUpdate'])->name('daily-work-updates.store');
    Route::post('/daily-work-updates/delete', [App\Http\Controllers\EmployeeController::class, 'deleteDailyWorkUpdate'])->name('daily-work-updates.delete');
});

require __DIR__.'/auth.php';
