<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureRoleAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\EnsureRoleStudent;
use App\Http\Middleware\EnsureRoleTeacher;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminScheduleController;

Route::get('/', function () { return view('home'); });

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', EnsureRoleStudent::class])->group(function () {
    Route::get('/student-dashboard', [StudentController::class, 'index'])->name('student.index');
    Route::get('/student-profile', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/enroll-course', [StudentController::class, 'enroll'])->name('student.enroll');
    Route::post('/unenroll-course', [StudentController::class, 'unenroll'])->name('student.unenroll');
    Route::get('/materi', [MaterialController::class, 'index'])->name('materials.index');
        Route::get('/materi/baca/{id}', [MaterialController::class, 'show'])->name('materials.show');
});

Route::middleware(['auth', EnsureRoleTeacher::class])->group(function () {
    Route::get('/instructor-dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
    Route::get('/upload-materi', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/upload-materi', [MaterialController::class, 'store'])->name('materials.store');
});

Route::middleware(['auth', EnsureRoleAdmin::class])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/edit', [AdminScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/admin/edit', [AdminScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::put('/admin/edit/{id}', [AdminScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/admin/edit/{id}', [AdminScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
});