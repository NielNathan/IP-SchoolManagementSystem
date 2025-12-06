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

        $studentGradeLevel = explode('-', $student->class_grade)[0];

        $wajibTaken = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan', 
            'Bahasa Indonesia', 
            'Bahasa Inggris', 
            'Matematika (Wajib)', 
            'PJOK',
            'Sejarah'
        ];

        // 1. AMBIL JADWAL SAYA (Bersihkan duplikat tampilan)
        $rawSchedules = $student->schedules()->with(['subject', 'instructor'])->get();
        
        $mySchedules = $rawSchedules->unique(function ($item) {
            return $item->subject_id . '-' . $item->day . '-' . $item->time_start;
        });

        // 2. BUAT "DAFTAR BLACKLIST" (Signature Mapel yang sudah diambil)
        // Kita simpan "SubjectID-Hari-Jam" yang sudah diambil siswa
        $takenSignatures = $mySchedules->map(function ($item) {
            return $item->subject_id . '-' . $item->day . '-' . $item->time_start;
        })->toArray();

        // 3. AMBIL SEMUA JADWAL SESUAI JENJANG
        $allSchedules = Schedule::with(['subject', 'instructor'])
            ->where('grade_level', $studentGradeLevel)
            ->get();

        // 4. FILTER JADWAL PILIHAN (LOGIKA BARU)
        // Buang jadwal jika "Signature"-nya ada di daftar blacklist
        // Ini akan membuang ID 102 jika ID 101 (mapel sama) sudah diambil.
        $allSchedules = $allSchedules->reject(function ($item) use ($takenSignatures) {
            $signature = $item->subject_id . '-' . $item->day . '-' . $item->time_start;
            return in_array($signature, $takenSignatures);
        });

        // 5. BERSIHKAN DUPLIKAT DI DAFTAR PILIHAN
        // Agar tidak muncul 5 baris "Biologi" yang sama
        $allSchedules = $allSchedules->unique(function ($s) {
            return $s->subject_id . '-' . $s->day . '-' . $s->time_start;
        })->values();

        $totalTaken = $mySchedules->count();

        return view('student.home', compact(
            'student', 'allSchedules', 'mySchedules', 'totalTaken', 'wajibTaken'
        ));
    }

    // ... (Function enroll, unenroll, dashboard biarkan tetap sama) ...
    public function enroll(Request $request)
    {
        $student = Auth::user()->student;
        $schedule = Schedule::find($request->schedule_id);
        
        // Cek apakah siswa SUDAH punya mapel ini (berdasarkan Subject ID)
        $alreadyHasSubject = $student->schedules()->whereHas('subject', function($q) use ($schedule) {
            $q->where('id', $schedule->subject_id);
        })->exists();

        if ($alreadyHasSubject) {
            return back()->with('error', 'Anda sudah mengambil mata pelajaran ini!');
        }

        if ($student->schedules()->count() >= 12) {
            return back()->with('error', 'Batas maksimum 12 mata pelajaran telah tercapai!');
        }

        try {
            $student->schedules()->attach($request->schedule_id);
            return back()->with('success', 'Berhasil mengambil mata pelajaran!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengambil mapel.');
        }
    }

    public function unenroll(Request $request)
    {
        $student = Auth::user()->student;
        $student->schedules()->detach($request->schedule_id);
        return back()->with('success', 'Mata pelajaran berhasil dihapus dari jadwal.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) return "Error.";
        return view('student.dashboard', compact('student'));
    }
}