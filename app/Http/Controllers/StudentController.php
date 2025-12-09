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

        $studentGradeLevel = explode('-', $student->class_grade)[0];

        $wajibTaken = [
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
        $availableWajibSchedules = Schedule::whereHas('subject', function($q) use ($wajibNames) {
            $q->whereIn('name', $wajibNames);
        })
        ->where('grade_level', $gradeLevel)
        ->with('subject')
        ->get();

        foreach ($wajibNames as $wajibName) {
            $targetName = strtolower(preg_replace('/\s+/', '', $wajibName));

            $hasMapel = $student->schedules()->get()->contains(function($s) use ($targetName) {
                return strtolower(preg_replace('/\s+/', '', $s->subject->name)) === $targetName;
            });

            if (!$hasMapel) {
                $scheduleToTake = $availableWajibSchedules->first(function($s) use ($targetName) {
                    return strtolower(preg_replace('/\s+/', '', $s->subject->name)) === $targetName;
                });

                if ($scheduleToTake) {
                    try {
                        $student->schedules()->attach($scheduleToTake->id);
                    } catch (\Exception $e) {
                        // Skip
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

        $gradeLevel = explode('-', $student->class_grade)[0];

        $rawSchedules = $student->schedules()
            ->with('subject')
            ->get();

        $schedules = $rawSchedules->map(function($schedule) use ($gradeLevel) {
            $materials = \App\Models\Material::where('subject_id', $schedule->subject->id)
                ->where('grade_level', $gradeLevel)
                ->latest()
                ->get();
            
            $schedule->materials = $materials;
            return $schedule;
        });

        $enrolledSchedules = $schedules->unique(function($item) {
            return strtolower(preg_replace('/\s+/', '', $item->subject->name));
        })->values();

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

            $currentTotal = $student->schedules()->count();

            if ($currentTotal >= 12) {
                return back()
                    ->with('success', 'Berhasil mengambil mata pelajaran!')
                    ->with('warning_full', true); 
            }
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
            'phone'    => 'nullable|string|max:20',
        ]);

        try {
            $user->update([
                'email' => $validated['email']
            ]);

            $student->update([
                'fullname' => $validated['fullname'],
                'phone'    => $request->phone 
            ]);

            return redirect()->route('student.edit-profile')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function finalizeSchedule()
    {
        $user = Auth::user();
        $student = $user->student;

        $student->is_schedule_finalized = true;
        $student->save();

        return redirect()->back()->with('success_finalized', true);
    }
}