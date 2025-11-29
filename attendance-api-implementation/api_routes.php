<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Attendance API Routes
| Base URL: /api/v1
|
*/

// Health check (public)
Route::get('/health', [AttendanceController::class, 'health']);

// Authentication
Route::post('/auth/token', [AuthController::class, 'token']);

// Protected routes (require JWT token)
Route::middleware(['auth:api'])->group(function () {

    // Process single day attendance
    Route::post('/attendance/process/single', [AttendanceController::class, 'processSingle']);

    // Process bulk attendance
    Route::post('/attendance/process/bulk', [AttendanceController::class, 'processBulk']);

});
