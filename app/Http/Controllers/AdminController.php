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
            'Matematika (Wajib)',
            'Sejarah Indonesia',
            'Bahasa Inggris',
            'Seni Budaya',
            'PJOK',
            'PKWU'
        ];

        $topClasses = Schedule::with('subject')
                    ->whereHas('subject', function($query) use ($wajibSubjects) {
                        $query->whereNotIn('name', $wajibSubjects);
                    })
                    ->withCount('students')
                    ->orderByDesc('students_count')
                    ->take(5)
                    ->get();

        $rawSchedules = Schedule::with(['subject', 'instructor', 'students'])->get();

        $groupedSchedules = $rawSchedules->groupBy('subject_id');

        $allClasses = $groupedSchedules->map(function($group) {

            $representative = $group->first();

            $mergedInstructors = $group->map(function($item){
                return $item->instructor;
            })->unique('id')->values();

            $mergedStudents = $group->flatMap->students->unique('id')->values();

            $representative->multi_instructors = $mergedInstructors;
            $representative->multi_students = $mergedStudents;

            return $representative;

        })
        ->sortBy(function($kelas) {
            return $kelas->subject->name; 
        })
        ->values();

        return view('admin.home', compact('totalSiswa', 'totalGuru', 'totalKelas', 'topClasses', 'allClasses'));
    }

    // Reset jadwal siswa jika ingin memilih ulang
    public function resetStudentSchedule($id)
    {
        $student = Student::find($id);
        
        $student->is_schedule_finalized = false;
        
        $student->save();

        return redirect()->back()->with('success', 'Status jadwal siswa berhasil di-reset. Siswa dapat mengambil mapel lagi.');
    }

    // Reset semua jadwal siswa apabila naik kelas
    public function resetAllSchedules()
    {
        $students = Student::all();
        
        foreach($students as $student) {
            $student->is_schedule_finalized = false;

            $student->schedules()->detach(); 
            
            $student->save();
        }
        
        return redirect()->back()->with('success', 'Semua jadwal siswa telah dikosongkan untuk semester baru.');
    }
}