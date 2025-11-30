<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;

// --- LOGIN CUSTOM KITA ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- REDIRECT ROOT ---
Route::get('/', function () { return redirect('/login'); });

// --- STUDENT ---
Route::middleware(['auth', \App\Http\Middleware\EnsureRoleStudent::class])->group(function () {
    Route::get('/student-dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/enroll-course', [StudentController::class, 'enroll'])->name('student.enroll');
});

// --- INSTRUCTOR ---
Route::middleware(['auth'])->group(function () {
    Route::get('/instructor-dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
});