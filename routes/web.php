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

Route::get('/', function () { return view('welcome'); })->name('welcome');


Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', EnsureRoleStudent::class])->group(function () {
    // 1. DASHBOARD UTAMA (JADWAL & PILIH MAPEL)
    // Arahkan ke method 'index' yang punya logika filter jadwal & auto-enroll
    Route::get('/student-dashboard', [StudentController::class, 'index'])->name('student.index');

    // 2. HALAMAN PROFIL
    // Arahkan ke method 'dashboard' (yang isinya profil siswa)
    Route::get('/student-profile', [StudentController::class, 'dashboard'])->name('student.dashboard');

    // 3. EDIT PROFIL
    Route::get('/student-edit-profile', [StudentController::class, 'editProfile'])->name('student.edit-profile');
    Route::put('/student-update-profile', [StudentController::class, 'updateProfile'])->name('student.update-profile');

    // 4. ACTION AMBIL/HAPUS KELAS
    Route::post('/enroll-course', [StudentController::class, 'enroll'])->name('student.enroll');
    Route::post('/unenroll-course', [StudentController::class, 'unenroll'])->name('student.unenroll');

    // 5. LIHAT MATERI (SISWA)
    Route::get('/student-materi', [StudentController::class, 'materi'])->name('student.materi');
    Route::get('/materi/baca/{id}', [MaterialController::class, 'show'])->name('materials.show');
    
    // Route cadangan jika ada link lama mengarah ke sini
    Route::get('/student-pilih', [StudentController::class, 'index'])->name('student.pilih');
});

Route::middleware(['auth', EnsureRoleTeacher::class])->group(function () {
    Route::get('/instructor-dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
    Route::get('/upload-materi', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/upload-materi', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materi-saya', [MaterialController::class, 'myMaterials'])->name('instructor.materials.index');
    Route::get('/materi-saya/{id}/edit', [MaterialController::class, 'editMaterial'])->name('instructor.materials.edit');
    Route::put('/materi-saya/{id}', [MaterialController::class, 'updateMaterial'])->name('instructor.materials.update');
    Route::delete('/materi-saya/{id}', [MaterialController::class, 'deleteMaterial'])->name('instructor.materials.delete');
    Route::post('/instructor/kick-student', [InstructorController::class, 'kickStudent'])->name('instructor.kick_student');
});

Route::middleware(['auth', EnsureRoleAdmin::class])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/edit', [AdminScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/admin/edit', [AdminScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::put('/admin/edit/{id}', [AdminScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/admin/edit/{id}', [AdminScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
});
