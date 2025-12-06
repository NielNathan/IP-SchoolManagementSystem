<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminScheduleController;

Route::get('/', function () { return view('home'); });

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', \App\Http\Middleware\EnsureRoleStudent::class])->group(function () {
    Route::get('/student-pilihmapel', [StudentController::class, 'index'])->name('student.index');
    Route::get('/student-pilih', function () { 
        $user = \Illuminate\Support\Facades\Auth::user();
        $student = $user->student; 
        if(!$student) return "Error: Data Profile Siswa tidak ditemukan.";
        
        $studentGradeLevel = explode('-', $student->class_grade)[0]; 
        $wajibTaken = ['Pendidikan Agama dan Budi Pekerti', 'Bahasa Indonesia', 'Bahasa Inggris', 'Matematika (Wajib)', 'PJOK'];
        $mySchedules = $student->schedules()->with(['subject', 'instructor'])->get();
        $takenSubjectIds = $mySchedules->pluck('subject_id')->toArray();
        
        $allSchedules = \App\Models\Schedule::with(['subject', 'instructor'])
                        ->whereNotIn('subject_id', $takenSubjectIds)
                        ->where('grade_level', $studentGradeLevel)
                        ->get();
        
        $totalTaken = $mySchedules->count();
        
        return view('student.pilih', compact('student', 'allSchedules', 'mySchedules', 'totalTaken', 'wajibTaken'));
    })->name('student.pilih');
    Route::get('/student-profile', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student-materi', [StudentController::class, 'materi'])->name('student.materi');
    Route::get('/student-edit-profile', [StudentController::class, 'editProfile'])->name('student.edit-profile');
    Route::put('/student-update-profile', [StudentController::class, 'updateProfile'])->name('student.update-profile');
    Route::post('/enroll-course', [StudentController::class, 'enroll'])->name('student.enroll');
    Route::post('/unenroll-course', [StudentController::class, 'unenroll'])->name('student.unenroll');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/instructor-dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
});

Route::middleware(['auth', \App\Http\Middleware\EnsureRoleAdmin::class])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/edit', [AdminScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/admin/edit', [AdminScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::put('/admin/edit/{id}', [AdminScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/admin/edit/{id}', [AdminScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
});