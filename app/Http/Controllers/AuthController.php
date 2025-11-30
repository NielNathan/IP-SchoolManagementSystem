<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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
            }

            // Kalau admin/role lain
            return redirect('/');
        }

        // Kalau Gagal Login
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