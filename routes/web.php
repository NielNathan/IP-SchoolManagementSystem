<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () { return redirect('/login'); });

Route::middleware(['auth', \App\Http\Middleware\EnsureRoleStudent::class])->group(function () {
    Route::get('/student-dashboard', [StudentController::class, 'index'])->name('student.index');
    Route::post('/enroll-course', [StudentController::class, 'enroll'])->name('student.enroll');
    Route::post('/unenroll-course', [StudentController::class, 'unenroll'])->name('student.unenroll');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/instructor-dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
});

Route::middleware(['auth', \App\Http\Middleware\EnsureRoleAdmin::class])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});