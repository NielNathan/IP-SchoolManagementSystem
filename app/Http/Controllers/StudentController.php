<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;
use App\Models\Subject;

class StudentController extends Controller
{
    // ==========================================================
    // 1. HALAMAN UTAMA (JADWAL SAYA & PILIH KELAS)
    // ==========================================================
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if(!$student) return "Error: Data Profile Siswa tidak ditemukan.";

        // Ambil jenjang kelas (misal "10" dari "10-IPA")
        $studentGradeLevel = explode('-', $student->class_grade)[0];

        // Daftar Nama Mapel Wajib (Harus sama persis dengan di Database)
        $wajibTaken = [
            'Pendidikan Agama dan Budi Pekerti', 
            'Pendidikan Pancasila dan Kewarganegaraan', 
            'Bahasa Indonesia', 
            'Bahasa Inggris', 
            'Matematika (Wajib)', 
            'PJOK', 
            'Sejarah'
        ];

        // ---------------------------------------------------------
        // FITUR BARU: AUTO-ASSIGN MAPEL WAJIB
        // ---------------------------------------------------------
        $this->autoEnrollWajib($student, $studentGradeLevel, $wajibTaken);
        // ---------------------------------------------------------

        // --- 1. AMBIL JADWAL SAYA (YANG SUDAH DIAMBIL/AUTO-ASSIGNED) ---
        // Refresh data jadwal setelah auto-enroll
        $rawSchedules = $student->schedules()->with(['subject', 'instructor'])->get();
        
        // Filter Tampilan (Satu mapel cukup tampil sekali)
        $mySchedules = $rawSchedules->unique(function ($item) {
            return strtolower(preg_replace('/\s+/', '', $item->subject->name)); 
        });

        // Simpan daftar mapel yang sudah diambil untuk blacklist pilihan
        $takenNamesNormalized = $mySchedules->map(function ($item) {
            return strtolower(preg_replace('/\s+/', '', $item->subject->name));
        })->toArray();

        // --- 2. AMBIL JADWAL TERSEDIA ---
        $allSchedulesRaw = Schedule::with(['subject', 'instructor'])
            ->where('grade_level', $studentGradeLevel)
            ->get();

        // --- 3. FILTER DAFTAR PILIHAN ---
        // Buang jadwal yang namanya sudah ada di jadwal saya
        $allSchedules = $allSchedulesRaw->reject(function ($item) use ($takenNamesNormalized) {
            $name = strtolower(preg_replace('/\s+/', '', $item->subject->name));
            return in_array($name, $takenNamesNormalized);
        });

        // Bersihkan duplikat di daftar pilihan
        $allSchedules = $allSchedules->unique(function ($s) {
            return strtolower(preg_replace('/\s+/', '', $s->subject->name));
        })->values();

        $totalTaken = $mySchedules->count();

        return view('student.pilih', compact(
            'student', 'allSchedules', 'mySchedules', 'totalTaken', 'wajibTaken'
        ));
    }

    // ==========================================================
    // FUNGSI BANTUAN: AUTO ENROLL LOGIC
    // ==========================================================
    private function autoEnrollWajib($student, $gradeLevel, $wajibNames)
    {
        // 1. Ambil semua jadwal mata pelajaran WAJIB untuk jenjang ini
        $availableWajibSchedules = Schedule::whereHas('subject', function($q) use ($wajibNames) {
            $q->whereIn('name', $wajibNames);
        })
        ->where('grade_level', $gradeLevel)
        ->with('subject')
        ->get();

        // 2. Cek satu per satu mapel wajib
        foreach ($wajibNames as $wajibName) {
            // Normalisasi nama wajib
            $targetName = strtolower(preg_replace('/\s+/', '', $wajibName));

            // Cek apakah siswa SUDAH punya mapel ini?
            $hasMapel = $student->schedules()->get()->contains(function($s) use ($targetName) {
                return strtolower(preg_replace('/\s+/', '', $s->subject->name)) === $targetName;
            });

            // JIKA BELUM PUNYA, CARIKAN JADWALNYA & MASUKKAN
            if (!$hasMapel) {
                // Cari jadwal yang namanya cocok
                $scheduleToTake = $availableWajibSchedules->first(function($s) use ($targetName) {
                    return strtolower(preg_replace('/\s+/', '', $s->subject->name)) === $targetName;
                });

                if ($scheduleToTake) {
                    // Masukkan ke jadwal siswa (Attach)
                    // Kita pakai try-catch biar kalau ada error duplikat id tidak crash
                    try {
                        $student->schedules()->attach($scheduleToTake->id);
                    } catch (\Exception $e) {
                        // Ignore error (mungkin race condition atau data kotor)
                    }
                }
            }
        }
    }

    // ==========================================================
    // 2. HALAMAN MATERI
    // ==========================================================
    public function materi()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');

        $rawSchedules = $student->schedules()
            ->with(['subject.materials', 'instructor'])
            ->get();

        $enrolledSchedules = $rawSchedules->unique(function($item) {
            return strtolower(preg_replace('/\s+/', '', $item->subject->name));
        });

        return view('student.materi', compact('enrolledSchedules'));
    }

    // ==========================================================
    // 3. PROSES AMBIL & BATAL KELAS
    // ==========================================================
    public function enroll(Request $request)
    {
        $student = Auth::user()->student;
        $schedule = Schedule::with('subject')->find($request->schedule_id);
        
        if(!$schedule) return back()->with('error', 'Jadwal tidak ditemukan');

        // Validasi Duplikat Nama Mapel
        $targetName = strtolower(preg_replace('/\s+/', '', $schedule->subject->name));
        
        $alreadyHasName = $student->schedules()->get()->contains(function ($enrolled) use ($targetName) {
            return strtolower(preg_replace('/\s+/', '', $enrolled->subject->name)) === $targetName;
        });

        if ($alreadyHasName) {
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

    // ==========================================================
    // 4. PROFIL & LAINNYA
    // ==========================================================
    public function dashboard() {
        $user = Auth::user();
        $student = $user->student;
        return view('student.dashboard', compact('student')); 
    
    }
    public function pilih() { return $this->index(); }

    public function editProfile()
    {
        $user = Auth::user();
        $student = $user->student;
        return view('student.edit-profile', compact('student', 'user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            $user->update(['email' => $validated['email']]);
            $student->update(['fullname' => $validated['fullname']]);

            if ($request->hasFile('photo')) {
                if ($student->photo && Storage::exists('public/' . $student->photo)) {
                    Storage::delete('public/' . $student->photo);
                }
                $path = $request->file('photo')->store('students', 'public');
                $student->update(['photo' => $path]);
            }

            return back()->with('success', 'Profil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}