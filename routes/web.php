<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/run-migrations', [App\Http\Controllers\Admin\ApiController::class, 'runMigrations']);
Route::get('/run-migrations-fresh', [App\Http\Controllers\Admin\ApiController::class, 'runMigrationsFresh']);
Route::get('/run-seeders', [App\Http\Controllers\Admin\ApiController::class, 'runSeeders']);

Route::get('/', function () {
    return view('welcome');
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
        Route::get('/branches', [App\Http\Controllers\Admin\ApiController::class, 'branches'])->name('branches');
        Route::post('/branches', [App\Http\Controllers\Admin\ApiController::class, 'storeBranch'])->name('branches.store');
        Route::post('/branches/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteBranch'])->name('branches.delete');
        Route::get('/daily-plans', [App\Http\Controllers\Admin\ApiController::class, 'dailyPlans'])->name('daily-plans');
        Route::post('/daily-plans', [App\Http\Controllers\Admin\ApiController::class, 'storeDailyPlan'])->name('daily-plans.store');
        Route::post('/daily-plans/update', [App\Http\Controllers\Admin\ApiController::class, 'updateDailyPlan'])->name('daily-plans.update');
        Route::post('/daily-plans/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteDailyPlan'])->name('daily-plans.delete');
        Route::post('/daily-plans/convert', [App\Http\Controllers\Admin\ApiController::class, 'convertDailyPlan'])->name('daily-plans.convert');
        Route::post('/daily-plans/reject', [App\Http\Controllers\Admin\ApiController::class, 'rejectDailyPlan'])->name('daily-plans.reject');
        Route::get('/leads', [App\Http\Controllers\Admin\ApiController::class, 'leads'])->name('leads');
        Route::get('/non-leads', [App\Http\Controllers\Admin\ApiController::class, 'nonLeads'])->name('non-leads');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
