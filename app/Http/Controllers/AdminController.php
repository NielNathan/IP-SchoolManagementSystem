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
        // --- BAGIAN STATISTIK ---
        $totalSiswa = Student::count();
        $totalGuru = Instructor::count();
        $totalKelas = Schedule::count();

        // --- BAGIAN TOP 5 KELAS ---
        $wajibSubjects = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Matematika (Wajib)',
            'PJOK',
            'Sejarah',
        ];

        $topClasses = Schedule::with('subject')
                    ->whereHas('subject', function($query) use ($wajibSubjects) {
                        $query->whereNotIn('name', $wajibSubjects);
                    })
                    ->withCount('students')
                    ->orderByDesc('students_count')
                    ->take(5)
                    ->get();

        // --- BAGIAN UTAMA (PERBAIKAN ERROR & DUPLIKAT) ---
        
        // 1. Ambil data mentah sebagai OBJECT (Model)
        $rawSchedules = Schedule::with(['subject', 'instructor', 'students'])->get();

        // 2. Lakukan Grouping di sini agar baris tidak dobel-dobel
        // Kunci Grouping: ID Mapel - ID Guru - Hari - Jam (07:00)
        $groupedSchedules = $rawSchedules->groupBy('subject_id');

        $allClasses = $groupedSchedules->map(function($group) {

            $representative = $group->first();

            // Gabung guru
            $mergedInstructors = $group->map(function($item){
                return $item->instructor;
            })->unique('id')->values();

            // Gabung siswa
            $mergedStudents = $group->flatMap->students->unique('id')->values();

            // Ambil waktu dan hari pertama (boleh di-custom nanti)
            $representative->multi_instructors = $mergedInstructors;
            $representative->multi_students = $mergedStudents;

            return $representative;

        })->values();

        // Kirim ke View (Data $allClasses sekarang berisi OBJECT bersih)
        return view('admin.home', compact('totalSiswa', 'totalGuru', 'totalKelas', 'topClasses', 'allClasses'));
    }
}