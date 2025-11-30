<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Schedule;

class StudentController extends Controller
{
    // Menampilkan Dashboard Siswa
    public function dashboard()
    {
        // 1. DUMMY LOGIN (Pastikan ini ada)
        $user = Auth::user();
    
    // Pastikan relasi sudah benar (hasOne di User, belongsTo di Student)
        $student = $user->student; 
        
        // Debugging: Cek apakah student ketemu
        if(!$student) {
            dd("Data User ditemukan, tapi data Siswa (Profile) belum ada. Cek Seeder.");
        }

        $allSchedules = \App\Models\Schedule::with(['subject', 'instructor'])->get();
        $mySchedules = $student->schedules()->with(['subject', 'instructor'])->get();
        
        // PERHATIKAN BAGIAN COMPACT INI
        // Tulisan 'student' harus sama persis dengan nama variabel $student
        return view('student.home', compact('student', 'allSchedules', 'mySchedules'));
    }

    // Memproses Pengambilan SKS (Saat tombol 'Ambil Kelas' diklik)
    public function storeEnrollment(Request $request)
    {
        // Simulasi Login lagi
        $studentId = 1;
        $student = Student::find($studentId);

        $scheduleId = $request->schedule_id;
        $schedule = Schedule::withCount('students')->find($scheduleId);

        // Validasi 1: Cek Kuota
        if ($schedule->students_count >= $schedule->quota) {
            return back()->with('error', 'Gagal! Kuota kelas ini sudah penuh.');
        }

        // Validasi 2 & Simpan: Cek apakah sudah ambil
        // Kita gunakan try-catch karena kita sudah set 'unique' di database migration
        try {
            // FUNGSI UTAMA MENYIMPAN KE DATABASE (Tabel Pivot enrollments)
            $student->schedules()->attach($scheduleId);
            
            return back()->with('success', 'Berhasil mengambil mata pelajaran!');
        } catch (\Exception $e) {
            return back()->with('error', 'Anda sudah mengambil kelas ini sebelumnya.');
        }
    }
}