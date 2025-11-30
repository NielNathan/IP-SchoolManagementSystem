<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// tampilan login n register
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

// logn & register
Route::post('/login_process', [AuthController::class, 'login_process'])->name('login.process');
Route::post('/register_process', [AuthController::class, 'register_process'])->name('register.process');


// dasbot murid
Route::get('/student/home', function () {
    return view('student.home');
})
->name('student.home')
->middleware([\App\Http\Middleware\EnsureRoleStudent::class]);

// dasbot guru
Route::get('/teacher/home', function () {
    return view('teacher.home');
})
->name('teacher.home')
->middleware([\App\Http\Middleware\EnsureRoleTeacher::class]);

// dasbot admin
Route::get('/admin/home', function () {
    return view('admin.home');
})
->name('admin.home')
->middleware([\App\Http\Middleware\EnsureRoleAdmin::class]);
