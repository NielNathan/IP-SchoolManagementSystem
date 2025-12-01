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

        if(!$student) {
            return "Error: Data Profile Siswa tidak ditemukan.";
        }

        // 1. DAFTAR NAMA MAPEL WAJIB (Copy dari Seeder/Auth)
        // Ini digunakan untuk membedakan mana yang boleh dihapus mana yang tidak
        $wajibTaken = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Matematika (Wajib)',
            'PJOK',
            'Sejarah',
        ];

        // 2. AMBIL JADWAL SAYA
        $mySchedules = $student->schedules()->with(['subject', 'instructor'])->get();

        // 3. FILTER JADWAL TERSEDIA
        $takenSubjectIds = $mySchedules->pluck('subject_id')->toArray();
        $allSchedules = Schedule::with(['subject', 'instructor'])
                        ->whereNotIn('subject_id', $takenSubjectIds)
                        ->get();

        $totalTaken = $mySchedules->count();

        // Kirim variabel $wajibTaken ke view
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
}