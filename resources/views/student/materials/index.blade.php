<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Materi Belajar - IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .header { margin-bottom: 30px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; }
        h1 { margin: 0; color: #111827; font-size: 24px; }
        p { color: #6b7280; margin: 5px 0 0; }

        /* Grid Layout */
        .materials-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 20px; 
        }

        /* Card Style */
        .material-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
            border-left: 5px solid #2563eb; /* Aksen Biru */
            transition: transform 0.2s;
        }
        .material-card:hover { transform: translateY(-3px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

        .material-card h3 { margin: 0 0 10px 0; font-size: 18px; color: #1f2937; }
        
        .meta-info { font-size: 13px; color: #6b7280; margin-bottom: 15px; line-height: 1.6; }
        .badge { background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }

        .btn-read { 
            display: block; 
            width: 100%; 
            text-align: center; 
            background-color: #2563eb; 
            color: white; 
            padding: 10px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: 600; 
            margin-top: 10px;
        }
        .btn-read:hover { background-color: #1d4ed8; }

        .empty-state { text-align: center; color: #9ca3af; padding: 40px; }
        .btn-back { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #4b5563; font-weight: 600; }
    </style>
</head>
<body>
    @include('partials.header-home') <div class="container">
        
        <a href="{{ url('/dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>

        <div class="header">
            <h1>📚 Materi Pembelajaran</h1>
            <p>Daftar materi yang tersedia untuk Kelas {{ explode('-', Auth::user()->student->class_grade)[0] }}</p>
        </div>

        @if($materials->isEmpty())
            <div class="empty-state">
                <h3>Belum ada materi tersedia 😔</h3>
                <p>Guru belum mengupload materi untuk kelasmu.</p>
            </div>
        @else
            <div class="materials-grid">
                @foreach($materials as $materi)
                <div class="material-card">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <h3>{{ $materi->title }}</h3>
                        <span class="badge">{{ $materi->subject->name }}</span>
                    </div>

                    <div class="meta-info">
                        <strong>Guru:</strong> {{ $materi->instructor->fullname ?? '-' }}<br>
                        <strong>Diupload:</strong> {{ $materi->created_at->format('d M Y') }}
                    </div>

                    <a href="{{ route('materials.show', $materi->id) }}" target="_blank" class="btn-read">
                        📖 Baca Materi
                    </a>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</body>
</html>