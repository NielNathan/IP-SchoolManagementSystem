<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function login_process(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable',
        ]);

        $password = $request->input('password');

        if ($request->filled('name')) {
            $credentials = ['name' => $request->input('name'), 'password' => $password];
        } else {
            return back()->withErrors(['name' => 'Please provide a name.'])->withInput();
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role ?? 'student';

            switch ($role) {
                case 'admin':
                    $redirect = route('admin.home');
                    break;
                case 'teacher':
                    $redirect = route('teacher.home');
                    break;
                case 'student':
                default:
                    $redirect = route('student.home');
                    break;
            }

            return redirect()->intended($redirect);
        }

        return back()->withErrors(['password' => 'The provided credentials do not match our records.'])->withInput();
    }

    public function register()
    {
        return view('auth.register');
    }

    public function register_process(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login')->with('success', 'Registration successful. You may now log in.');
    }
}
