<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // =========================================================================
    // BAGIAN GURU (UPLOAD MATERI)
    // =========================================================================
    public function create()
    {
        // Pastikan yang login adalah Guru
        if (!Auth::user()->instructor) {
            abort(403, 'Akses ditolak. Halaman ini khusus Guru.');
        }

        $instructorId = Auth::user()->instructor->id;

        // Ambil mapel yang diajar guru ini
        $subjects = Schedule::where('instructor_id', $instructorId)
                            ->with('subject')
                            ->get()
                            ->pluck('subject')
                            ->unique('id');

        // Ambil daftar kelas yang diajar
        $grades = Schedule::where('instructor_id', $instructorId)
                            ->select('grade_level')
                            ->distinct()
                            ->orderBy('grade_level')
                            ->pluck('grade_level');

        return view('instructor.materials.create', compact('subjects', 'grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subject_id' => 'required',
            'grade_level' => 'required',
            'file' => 'required|mimes:pdf,jpg,png,doc,docx,ppt,pptx|max:10000',
        ]);

        if ($request->file('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('materials', $fileName, 'public');
        }

        Material::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'instructor_id' => Auth::user()->instructor->id,
            'grade_level' => $request->grade_level,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Materi berhasil dipublish!');
    }

    // =========================================================================
    // BAGIAN SISWA (LIHAT MATERI)
    // =========================================================================
    public function index()
    {
        // Pastikan yang login adalah Siswa
        if (!Auth::user()->student) {
            abort(403, 'Akses ditolak. Halaman ini khusus Siswa.');
        }

        $student = Auth::user()->student; 

        // Perbaikan: Gunakan 'class_grade' bukan 'level_grade'
        // Ambil jenjang kelas (misal "10" dari "10-A")
        $studentLevel = explode('-', $student->class_grade)[0]; 

        // Ambil materi yang sesuai jenjang kelas siswa
        $materials = Material::with(['subject', 'instructor'])
                        ->where('grade_level', $studentLevel)
                        ->latest()
                        ->get();

        return view('student.materials.index', compact('materials'));
    }

    public function show($id)
    {
        $material = Material::findOrFail($id);
        $path = storage_path('app/public/' . $material->file_path);

        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->file($path);
    }
}