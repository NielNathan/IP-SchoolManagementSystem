@extends('layouts.app')

@section('title', 'Edit Materi')

@section('custom-styles')
.card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
h1 { color: white; font-size: 28px; margin-bottom: 30px; text-align: center; }
.form-group { margin-bottom: 20px; }
label { display: block; margin-bottom: 8px; color: #111827; font-weight: 600; font-size: 14px; }
input, select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit; font-size: 14px; }
input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.file-info { background: #f3f4f6; padding: 12px; border-radius: 6px; margin-bottom: 10px; font-size: 13px; color: #6b7280; }
.btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
.btn-primary { background: #2563eb; color: white; }
.btn-primary:hover { background: #1e40af; }
.btn-secondary { background: #6b7280; color: white; }
.btn-secondary:hover { background: #4b5563; }
.button-group { display: flex; gap: 12px; }
.error-message { color: #ef4444; font-size: 12px; margin-top: 4px; }
.alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
@endsection

@section('content')
<h1>✏️ Edit Materi</h1>

<div class="card">
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Gagal memvalidasi data. Periksa kembali:</strong>
            <ul style="margin: 8px 0 0 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('instructor.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Judul Materi</label>
            <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}" required>
            @error('title') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="subject_id">Mata Pelajaran</label>
            <select id="subject_id" name="subject_id" required>
                <option value="">Pilih Mata Pelajaran</option>
                @if($subjects->isNotEmpty())
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                @endif
            </select>
            @error('subject_id') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="grade_level">Tingkat Kelas</label>
            <select id="grade_level" name="grade_level" required>
                <option value="">Pilih Tingkat Kelas</option>
                @if($grades->isNotEmpty())
                    @foreach($grades as $grade)
                    <option value="{{ $grade }}" {{ old('grade_level', $material->grade_level) == $grade ? 'selected' : '' }}>
                        Kelas {{ $grade }}
                    </option>
                    @endforeach
                @else
                    <option value="7" {{ old('grade_level', $material->grade_level) == '7' ? 'selected' : '' }}>Kelas 7</option>
                    <option value="8" {{ old('grade_level', $material->grade_level) == '8' ? 'selected' : '' }}>Kelas 8</option>
                    <option value="9" {{ old('grade_level', $material->grade_level) == '9' ? 'selected' : '' }}>Kelas 9</option>
                @endif
            </select>
            @error('grade_level') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>File Materi</label>
            @if($material->file_path)
                <div class="file-info">
                    📄 File saat ini: <strong>{{ basename($material->file_path) }}</strong>
                    <br><small style="color: #9ca3af;">Pilih file baru di bawah untuk mengganti, atau kosongkan untuk tetap menggunakan file saat ini.</small>
                </div>
            @endif
            <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip">
            @error('file') <span class="error-message">{{ $message }}</span> @enderror
            <small style="color: #6b7280; display: block; margin-top: 4px;">
                Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, PNG, MP4, ZIP (Max: 50MB)
            </small>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="{{ route('instructor.materials.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </form>
</div>
@endsection
