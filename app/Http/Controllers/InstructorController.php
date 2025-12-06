<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Student; 

class InstructorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $instructor = $user->instructor; //Ambil profil guru dari login

        if(!$instructor) {
            return "Error: Akun guru ini belum punya profil NIP. Cek Seeder.";
        }

        $myClasses = Schedule::where('instructor_id', $instructor->id)
                     ->with(['subject', 'students'])
                     ->get();

        return view('teacher.home', compact('instructor', 'myClasses'));
    }
    public function kickStudent(Request $request)
    {

        $request->validate([
            'nis' => 'required|string',    
            'class_id' => 'required|integer', 
        ]);

        $nis = $request->nis;
        $classId = $request->class_id;

        $schedule = Schedule::find($classId);

        if (!$schedule || $schedule->instructor_id !== Auth::user()->instructor->id) {
            return response()->json([
                'success' => false, 
                'message' => 'Jadwal tidak ditemukan atau Anda tidak memiliki otoritas untuk kelas ini.'
            ], 403);
        }

        $student = Student::where('nis', $nis)->first();

        if (!$student) {
            return response()->json([
                'success' => false, 
                'message' => 'Siswa dengan NIS tersebut tidak ditemukan.'
            ], 404);
        }

        try {

            $detachedCount = $schedule->students()->detach($student->id); 
            
            if ($detachedCount) {
                return response()->json([
                    'success' => true, 
                    'message' => "Siswa {$student->fullname} berhasil dikeluarkan dari kelas."
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal mengeluarkan siswa. Siswa mungkin tidak terdaftar di kelas ini.'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan server saat memproses data.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}