<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;

class MaterialController extends Controller
{
    public function create()
    {
        $student = Auth::user()->student; 

        // 2. Ambil angkatan/tingkat kelasnya (misal: "10-A" diambil "10"-nya saja)
        // Asumsi format kelas di databasemu adalah "10-A", "11-IPA", dsb.
        $studentLevel = explode('-', $student->class_grade)[0]; 

        // 3. Ambil materi yang sesuai dengan tingkat kelas siswa
        $materials = Material::with(['subject', 'instructor'])
                        ->where('grade_level', $studentLevel) // Filter Kelas
                        ->latest()
                        ->get();

        return view('student.materials.index', compact('materials'));
        
        $instructorId = Auth::user()->instructor->id;

        $subjects = Schedule::where('instructor_id', $instructorId)
                            ->with('subject')
                            ->get()
                            ->pluck('subject')
                            ->unique('id');

        $grades = Schedule::where('instructor_id', $instructorId)
                            ->select('grade_level')
                            ->distinct()
                            ->orderBy('grade_level')
                            ->pluck('grade_level');

        return view('instructor.materials.create', compact('subjects', 'grades'));
    }

    // --- GURU: SIMPAN FILE ---
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subject_id' => 'required',
            'file' => 'required|mimes:pdf,jpg,png|max:5000', // SAYA SARANKAN PDF AGAR BISA DIBACA
        ]);

        if ($request->file('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            // Simpan ke storage/app/public/materials
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

    // --- SISWA: LIST MATERI ---
    public function index()
    {
        $materials = Material::with(['subject', 'instructor'])->latest()->get();
        return view('student.materials.index', compact('materials'));
    }

    // --- SISWA: BACA FILE (SHOW) ---
    public function show($id)
    {
        $material = Material::findOrFail($id);
        
        // Ambil path lengkap file
        $path = storage_path('app/public/' . $material->file_path);

        // Cek file ada atau tidak
        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Tampilkan file di browser (Inline)
        return response()->file($path);
    }
}