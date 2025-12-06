<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Subject;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student; 
        if(!$student) return "Error: Data Profile Siswa tidak ditemukan.";

        // --- DETEKSI JENJANG SISWA ---
        // Misal kelas "XI-1", kita ambil "XI"-nya saja.
        // Caranya: Ambil kata sebelum tanda strip (-)
        $studentGradeLevel = explode('-', $student->class_grade)[0]; 

        $wajibTaken = ['Pendidikan Agama dan Budi Pekerti', 'Bahasa Indonesia', 'Bahasa Inggris', 'Matematika (Wajib)', 'PJOK'];

        $mySchedules = $student->schedules()->with(['subject', 'instructor'])->get();
        $takenSubjectIds = $mySchedules->pluck('subject_id')->toArray();

        // --- FILTER JADWAL ---
        // 1. Bukan jadwal yang sudah diambil
        // 2. HANYA jadwal yang jenjangnya cocok dengan siswa (grade_level == XI)
        $allSchedules = \App\Models\Schedule::with(['subject', 'instructor'])
                        ->whereNotIn('subject_id', $takenSubjectIds)
                        ->where('grade_level', $studentGradeLevel) // <--- FILTER KUNCI
                        ->get();

        $totalTaken = $mySchedules->count();

        return view('student.home', compact('student', 'allSchedules', 'mySchedules', 'totalTaken', 'wajibTaken'));
    }

    public function enroll(Request $request)
    {
        $student = Auth::user()->student;
        
        // UPDATE: BATAS MAKSIMAL JADI 12
        if ($student->schedules()->count() >= 12) {
            return back()->with('error', 'Batas maksimum 12 mata pelajaran telah tercapai!');
        }

        try {
            $student->schedules()->attach($request->schedule_id);
            return back()->with('success', 'Berhasil mengambil mata pelajaran!');
        } catch (\Exception $e) {
            return back()->with('error', 'Mata pelajaran ini sudah diambil.');
        }
    }

    // FUNGSI HAPUS KELAS (UNENROLL/DROP) - BARU
    public function unenroll(Request $request)
    {
        $student = Auth::user()->student;

        // Hapus (Detach) jadwal dari siswa
        $student->schedules()->detach($request->schedule_id);

        return back()->with('success', 'Mata pelajaran berhasil dihapus dari jadwal.');
    }

    // DASHBOARD - Tampilkan Profil Student
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return "Error: Data Profile Siswa tidak ditemukan.";
        }

        return view('student.dashboard', compact('student'));
    }

    // MATERI - Tampilkan Materi dari Mata Pelajaran yang Dipilih
    public function materi()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return "Error: Data Profile Siswa tidak ditemukan.";
        }

        // Ambil jadwal yang sudah diambil siswa beserta subject dan materialnya
        $enrolledSchedules = $student->schedules()
                                    ->with(['subject' => function($query) {
                                        $query->with('materials');
                                    }, 'instructor'])
                                    ->get();

        return view('student.materi', compact('student', 'enrolledSchedules'));
    }
}