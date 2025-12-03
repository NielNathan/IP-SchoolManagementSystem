<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 2. PROSES REGISTER (SIMPAN KE DATABASE)
    public function register(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email gaboleh kembar
            'password' => 'required|string|min:6',
            'nis' => 'required|string|unique:students', // NIS gaboleh kembar
            'class_grade' => 'required|string',
        ]);

        // B. Buat Akun User (Login)
        $user = User::create([
            'name' => $request->fullname, // Pakai nama lengkap buat nama user
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Otomatis jadi siswa
        ]);

        // C. Buat Profil Siswa (Biodata)
        Student::create([
            'user_id' => $user->id, // Sambungkan ke user yang baru dibuat
            'nis' => $request->nis,
            'fullname' => $request->fullname,
            'class_grade' => $request->class_grade,
        ]);

        $wajibSubjects = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Matematika (Wajib)',
            'PJOK',
            'Sejarah',
        ];

        // 2. Cari Jadwalnya dan Masukkan ke Siswa Baru
        foreach ($wajibSubjects as $namaMapel) {
            // Cari Subject berdasarkan nama
            $subject = Subject::where('name', $namaMapel)->first();
            
            if ($subject) {
                // Cari salah satu jadwal dari subject ini (ambil yang pertama aja)
                $schedule = Schedule::where('subject_id', $subject->id)->first();
                
                if ($schedule) {
                    // Masukkan ke KRS siswa baru
                    $newStudent = \App\Models\Student::where('user_id', $user->id)->first();
                    $newStudent->schedules()->attach($schedule->id);
                }
            }
        }
        // D. Langsung Login Otomatis
        Auth::login($user);
        return redirect()->route('student.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang.');
    }
    
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file blade ada di folder resources/views/auth/login.blade.php
    }

    // 2. Proses Login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba Login
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // LOGIKA PENGARAHAN (REDIRECT)
            $role = Auth::user()->role;

            if ($role === 'student') {
                return redirect()->route('student.dashboard');
            } elseif ($role === 'teacher') {
                return redirect()->route('instructor.dashboard');
            } elseif ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}