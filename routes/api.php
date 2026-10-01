<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| JSON endpoints used by the CRM admin dashboard. Prefix: /api
*/

Route::get('/run-migrations', [App\Http\Controllers\Admin\ApiController::class, 'runMigrations']);
Route::get('/run-migrations-fresh', [App\Http\Controllers\Admin\ApiController::class, 'runMigrationsFresh']);
Route::get('/run-seeders', [App\Http\Controllers\Admin\ApiController::class, 'runSeeders']);

Route::prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/dashboard-stats', [App\Http\Controllers\Admin\ApiController::class, 'dashboardStats'])->name('dashboard-stats');
    Route::get('/branches', [App\Http\Controllers\Admin\ApiController::class, 'branches'])->name('branches');
    Route::post('/branches', [App\Http\Controllers\Admin\ApiController::class, 'storeBranch'])->name('branches.store');
    Route::post('/branches/delete', [App\Http\Controllers\Admin\ApiController::class, 'deleteBranch'])->name('branches.delete');
});
