<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ShiftReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    }
    return redirect()->route('login');
});

// Redirect dashboard based on user role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('employee.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/users/{user}/history', [AdminController::class, 'userHistory'])->name('users.history');

    // Checklist management
    Route::get('/checklists', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::get('/checklists/create', [ChecklistController::class, 'create'])->name('checklists.create');
    Route::post('/checklists', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::get('/checklists/{checklist}/edit', [ChecklistController::class, 'edit'])->name('checklists.edit');
    Route::put('/checklists/{checklist}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::delete('/checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');

    // Assignment management
    Route::get('/checklists/assign', [ChecklistController::class, 'assignForm'])->name('checklists.assign');
    Route::post('/checklists/assign', [ChecklistController::class, 'assign'])->name('checklists.assign.store');
    Route::get('/checklists/assignments', [ChecklistController::class, 'assignments'])->name('checklists.assignments');

    // QR code management
    Route::get('/qr-codes', [QrController::class, 'index'])->name('qr-codes.index');
    Route::get('/qr-codes/create', [QrController::class, 'create'])->name('qr-codes.create');
    Route::post('/qr-codes', [QrController::class, 'store'])->name('qr-codes.store');
    Route::delete('/qr-codes/{qrCode}', [QrController::class, 'destroy'])->name('qr-codes.destroy');
    Route::get('/qr-codes/{qrCode}/download', [QrController::class, 'download'])->name('qr-codes.download');
    Route::get('/qr-codes/history', [QrController::class, 'scanHistory'])->name('qr-codes.history');
    Route::get('/qr-codes/history/export', [QrController::class, 'exportHistory'])->name('qr-codes.history.export');

    // Submissions
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/user/{userId}', [SubmissionController::class, 'byUser'])->name('submissions.by-user');

    // Shift Reports
    Route::get('/shift-reports', [ShiftReportController::class, 'index'])->name('shift-reports.index');
    Route::get('/shift-reports/user/{user}', [ShiftReportController::class, 'userDetail'])->name('shift-reports.user-detail');
    Route::get('/shift-reports/weekly', [ShiftReportController::class, 'weeklyReport'])->name('shift-reports.weekly');
    Route::get('/shift-reports/monthly', [ShiftReportController::class, 'monthlyReport'])->name('shift-reports.monthly');
});

// Employee routes
Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/qr-scanner', [EmployeeController::class, 'qrScanner'])->name('qr-scanner');
    Route::get('/today-tasks', [EmployeeController::class, 'todayTasks'])->name('today-tasks');
    Route::get('/task-history', [EmployeeController::class, 'taskHistory'])->name('task-history');
    Route::get('/qr-history', [EmployeeController::class, 'qrHistory'])->name('qr-history');

    // Submissions
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{assignment}', [SubmissionController::class, 'show'])->name('submissions.show');
});

// QR scan API (accessible by both admin and employee)
Route::middleware('auth')->group(function () {
    Route::post('/qr-scan', [QrController::class, 'scan'])->name('qr-scan');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
