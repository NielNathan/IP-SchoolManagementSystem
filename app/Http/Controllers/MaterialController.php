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
    // Upload Materi (Guru)
    public function create()
    {
        // Pastikan yang login punya Role Guru
        if (!Auth::user()->instructor) {
            abort(403, 'Akses ditolak. Halaman ini khusus Guru.');
        }

        $instructorId = Auth::user()->instructor->id;

        // Ambil mapel yang diajar guru tersebut
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

    // Download Materi (Siswa)
    public function index()
    {
        if (!Auth::user()->student) {
            abort(403, 'Akses ditolak. Halaman ini khusus Siswa.');
        }

        $student = Auth::user()->student; 

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



    // CRUD Materi (Guru)
    public function myMaterials()
    {
        if (!Auth::user()->instructor) {
            abort(403, 'Akses ditolak. Halaman ini khusus Guru.');
        }

        $instructorId = Auth::user()->instructor->id;
        $materials = Material::where('instructor_id', $instructorId)
                            ->with(['subject'])
                            ->latest()
                            ->get();

        return view('instructor.materials.index', compact('materials'));
    }

    // Edit materi
    public function editMaterial($id)
    {
        if (!Auth::user()->instructor) {
            abort(403, 'Akses ditolak. Halaman ini khusus Guru.');
        }

        $material = Material::findOrFail($id);

        // Pastikan guru hanya bisa edit materi miliknya sendiri
        if ($material->instructor_id !== Auth::user()->instructor->id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

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

        return view('instructor.materials.edit', compact('material', 'subjects', 'grades'));
    }

    // Update materi
    public function updateMaterial(Request $request, $id)
    {
        if (!Auth::user()->instructor) {
            abort(403, 'Akses ditolak. Halaman ini khusus Guru.');
        }

        $material = Material::findOrFail($id);

        if ($material->instructor_id !== Auth::user()->instructor->id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $request->validate([
            'title' => 'required',
            'subject_id' => 'required',
            'grade_level' => 'required',
            'file' => 'nullable|mimes:pdf,jpg,png,doc,docx,ppt,pptx|max:10000',
        ]);

        $updateData = [
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'grade_level' => $request->grade_level,
        ];

        // Jika ada file baru, hapus file lama dan simpan yang baru
        if ($request->file('file')) {
            if ($material->file_path && Storage::exists('public/' . $material->file_path)) {
                Storage::delete('public/' . $material->file_path);
            }

            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('materials', $fileName, 'public');
            $updateData['file_path'] = $path;
        }

        $material->update($updateData);

        return redirect()->route('instructor.materials.index')->with('success', 'Materi berhasil diperbarui!');
    }

    // Hapus materi
    public function deleteMaterial($id)
    {
        if (!Auth::user()->instructor) {
            abort(403, 'Akses ditolak. Halaman ini khusus Guru.');
        }

        $material = Material::findOrFail($id);

        if ($material->instructor_id !== Auth::user()->instructor->id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        // Hapus file
        if ($material->file_path && Storage::exists('public/' . $material->file_path)) {
            Storage::delete('public/' . $material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus!');
    }
}