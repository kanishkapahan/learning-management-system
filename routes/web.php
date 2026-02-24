<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', SearchController::class)->name('search.global');

    Route::resource('students', StudentController::class)->except(['show'])->middleware('permission:students.view');
    Route::post('/students/import', [StudentController::class, 'importCsv'])->name('students.import')->middleware('permission:students.import');
    Route::get('/students-import', [StudentController::class, 'importForm'])->name('students.import.form')->middleware('permission:students.import');
    Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore')->middleware('permission:students.restore');

    Route::resource('lecturers', LecturerController::class)->except(['show'])->middleware('permission:lecturers.view');
    Route::resource('courses', CourseController::class)->except(['show'])->middleware('permission:courses.view');
    Route::resource('batches', BatchController::class)->except(['show'])->middleware('permission:batches.view');

    Route::get('/enrollments/bulk', [EnrollmentController::class, 'create'])->name('enrollments.create')->middleware('permission:enrollments.create');
    Route::post('/enrollments/bulk', [EnrollmentController::class, 'store'])->name('enrollments.store')->middleware('permission:enrollments.create');

    Route::get('/exams/timetable', [ExamController::class, 'timetable'])->name('exams.timetable')->middleware('permission:exams.view');
    Route::resource('exams', ExamController::class)->except(['show'])->middleware('permission:exams.view');

    Route::resource('results', ResultController::class)->only(['index', 'create', 'store'])->middleware('permission:results.view');
    Route::post('/results/{result}/approve', [ResultController::class, 'approve'])->name('results.approve')->middleware('permission:results.approve');
    Route::post('/results/{result}/publish', [ResultController::class, 'publish'])->name('results.publish')->middleware('permission:results.publish');
    Route::get('/results-import', [ResultController::class, 'bulkUploadForm'])->name('results.import.form')->middleware('permission:results.import');
    Route::post('/results-import', [ResultController::class, 'bulkUpload'])->name('results.import')->middleware('permission:results.import');
    Route::post('/results/recalculate/{exam}', [ResultController::class, 'recalculate'])->name('results.recalculate')->middleware('permission:results.recalculate');

    Route::get('/attendance/mark', [AttendanceController::class, 'create'])->name('attendance.create')->middleware('permission:attendance.mark');
    Route::post('/attendance/mark', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('permission:attendance.mark');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report')->middleware('permission:attendance.view');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index')->middleware('permission:announcements.view');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store')->middleware('permission:announcements.create');

    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index')->middleware('permission:resources.view');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store')->middleware('permission:resources.create');
    Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download')->middleware('permission:resources.download');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:reports.view');
    Route::get('/reports/export/students', [ReportController::class, 'exportStudents'])->name('reports.export.students')->middleware('permission:reports.export');
    Route::get('/reports/export/pass-rates', [ReportController::class, 'exportPassRates'])->name('reports.export.pass-rates')->middleware('permission:reports.export');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit')->middleware('permission:settings.manage');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('permission:settings.manage');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
