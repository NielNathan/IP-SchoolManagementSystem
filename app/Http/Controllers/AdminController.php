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
        $totalSiswa = Student::count();
        $totalGuru = Instructor::count();
        $totalKelas = Schedule::count();

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

        $rawSchedules = \App\Models\Schedule::with(['subject', 'instructor', 'students'])->get();

        // Grouping berdasarkan Mapel + Guru + Hari + Jam (Tanpa Detik)
        $groupedSchedules = $rawSchedules->groupBy(function($item) {
            // Ambil jam menit saja (07:00) biar lebih fleksibel
            $timeStart = substr($item->time_start, 0, 5);
            return $item->subject_id . '-' . $item->instructor_id . '-' . $item->day . '-' . $timeStart;
        });

        // Mapping ulang
        $allClasses = $groupedSchedules->map(function($group) {
            $representative = $group->first();

            // Gabungkan siswa dari semua jenjang, TAPI HANYA AMBIL YANG UNIK
            $mergedStudents = $group->flatMap(function($schedule) {
                return $schedule->students;
            })->unique('id'); // <--- INI PERBAIKAN PENTINGNYA (Hapus Duplikat Siswa)

            // Masukkan kembali ke relasi
            $representative->setRelation('students', $mergedStudents);

            // Kita simpan info jenjang apa saja yang ada di grup ini (opsional, buat display)
            $representative->all_grades = $group->pluck('grade_level')->unique()->implode(', ');

            return $representative;
        })->values(); // Reset key array biar urut

        return view('admin.home', compact('totalSiswa', 'totalGuru', 'totalKelas', 'topClasses', 'allClasses'));
    }
}