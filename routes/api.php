<?php

use App\Http\Controllers\Api\BatchAnnouncementApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\StudentPortalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/me', MeController::class);
    Route::get('/student/results', [StudentPortalController::class, 'results']);
    Route::get('/student/attendance', [StudentPortalController::class, 'attendance']);
});

Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/batches/{id}/announcements', [BatchAnnouncementApiController::class, 'index']);
