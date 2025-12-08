<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Materi - IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        h1 { margin: 0 0 20px 0; color: #111827; font-size: 24px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; }
        
        /* Form Styles */
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; }
        
        input[type="text"], 
        select, 
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-family: inherit;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus, select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .helper-text { font-size: 12px; color: #6b7280; margin-top: 5px; }

        /* Buttons */
        .btn-group { display: flex; gap: 10px; margin-top: 30px; }
        
        .btn-save {
            background-color: #2563eb; color: white; border: none; padding: 10px 20px;
            border-radius: 6px; cursor: pointer; font-weight: 600; flex: 1;
        }
        .btn-save:hover { background-color: #1d4ed8; }

        .btn-back {
            background-color: #9ca3af; color: white; border: none; padding: 10px 20px;
            border-radius: 6px; cursor: pointer; font-weight: 600; text-decoration: none; text-align: center;
        }
        .btn-back:hover { background-color: #6b7280; }

        /* Alert Success */
        .alert-success {
            background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body>
    @include('partials.header-home')

    <div class="container">
        <h1>📤 Upload Materi Baru</h1>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="title">Judul Materi</label>
                <input type="text" name="title" id="title" placeholder="Contoh: Modul Bab 1 - Aljabar Linear" required>
            </div>

            <div class="form-group">
                <label for="subject_id">Mata Pelajaran</label>
                <select name="subject_id" id="subject_id" required>
                    <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="grade_level">Kelas</label>
                <select name="grade_level" id="grade_level" required>
                    <option value="" disabled selected>-- Pilih Tingkat Kelas --</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}">Kelas {{ $grade }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="file">File Materi</label>
                <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip" required>
                <p class="helper-text">*Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, PNG, MP4, ZIP (Max: 50MB)</p>
            </div>

            <div class="btn-group">
                <a href="{{ route('instructor.dashboard') }}" class="btn-back">Kembali</a>
                <button type="submit" class="btn-save">Simpan Materi</button>
            </div>
        </form>
    </div>
</body>
</html>