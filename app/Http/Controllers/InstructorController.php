<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class InstructorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $instructor = $user->instructor; // Ambil profil guru dari login

        if(!$instructor) {
            return "Error: Akun guru ini belum punya profil NIP. Cek Seeder.";
        }

        // Ambil jadwal mengajar guru ini + Data Siswa di dalamnya
        $myClasses = Schedule::where('instructor_id', $instructor->id)
                    ->with(['subject', 'students'])
                    ->get();

        return view('teacher.home', compact('instructor', 'myClasses'));
    }
}