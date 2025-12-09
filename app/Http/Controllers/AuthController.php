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

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:6',
            'nis' => 'required|string|unique:students', 
            'class_grade' => 'required|string',
        ]);

        // Akun User (Login)
        $user = User::create([
            'name' => $request->fullname, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', 
        ]);

        // Profil Siswa (Biodata)
        Student::create([
            'user_id' => $user->id, 
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

        // Otomatis menambahkan Mata Pelajaran Wajib ke KRS Siswa Baru
        foreach ($wajibSubjects as $namaMapel) {
            $subject = Subject::where('name', $namaMapel)->first();
            
            if ($subject) {
                $schedule = Schedule::where('subject_id', $subject->id)->first();
                if ($schedule) {
                    $newStudent = \App\Models\Student::where('user_id', $user->id)->first();
                    $newStudent->schedules()->attach($schedule->id);
                }
            }
        }

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang.');
    }
    
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // Logika Redirect Berdasarkan Role
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}