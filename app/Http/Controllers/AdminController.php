<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Instructor;

class AdminController extends Controller
{
    public function index()
    {
        // 1. DATA STATISTIK (Ringkasan)
        $totalSiswa = Student::count();
        $totalGuru = Instructor::count();
        $totalKelas = Schedule::count();

        // 2. STATISTIK MAPEL TERPOPULER
        // Ambil semua jadwal, hitung jumlah muridnya, lalu urutkan dari yang terbanyak
        $topClasses = Schedule::with('subject')
                    ->withCount('students') // Menghitung jumlah siswa per kelas
                    ->orderByDesc('students_count') // Urutkan dari terbanyak
                    ->take(5) // Ambil 5 besar
                    ->get();

        // 3. DATA LENGKAP SEMUA KELAS
        // Ambil jadwal beserta Relasi Subject, Instructor, dan Students
        $allClasses = Schedule::with(['subject', 'instructor', 'students'])->get();

        return view('admin.home', compact('totalSiswa', 'totalGuru', 'totalKelas', 'topClasses', 'allClasses'));
    }
}