<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Materi Siswa - IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }
        
        .header-section h1 {
            color: #111827;
            font-size: 28px;
            margin: 0;
        }
        
        .logout-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: #dc2626;
        }

        .navigation-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            font-size: 14px;
            display: inline-block;
        }

        .nav-btn-primary {
            background: #2563eb;
            color: white;
        }

        .nav-btn-primary:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .nav-btn-secondary {
            background: white;
            color: #2563eb;
            border: 2px solid #2563eb;
        }

        .nav-btn-secondary:hover {
            background: #eff6ff;
        }

        .nav-btn-tertiary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .nav-btn-tertiary:hover {
            background: #f3f4ff;
        }
        
        .subject-section {
            margin-bottom: 40px;
        }

        .subject-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .subject-header h2 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .subject-header p {
            opacity: 0.9;
            font-size: 14px;
            margin: 0;
        }

        .materials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .material-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .material-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .material-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .material-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .material-description {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .material-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #9ca3af;
            padding-top: 12px;
            border-top: 1px solid #f3f4f6;
        }

        .download-btn {
            padding: 8px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 12px;
        }

        .download-btn:hover {
            background: #1e40af;
        }

        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px dashed #e5e7eb;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            color: #d1d5db;
            margin-bottom: 24px;
        }

        .empty-state h3 {
            color: #111827;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .container { padding: 20px; }
            .materials-grid { grid-template-columns: 1fr; }
            .header-section { flex-direction: column; gap: 15px; align-items: flex-start; }
            .navigation-buttons { flex-direction: column; }
            .nav-btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    @include('partials.header-home')
    
    <div class="container">
        <div class="header-section">
            <a href="{{ route('student.dashboard') }}" class="nav-btn nav-btn-secondary">
                 Kembali
            </a>
            <h1>📚 Materi Siswa</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        @forelse($enrolledSchedules as $schedule)
            <div class="subject-section">
                <div class="subject-header">
                    <h2>{{ $schedule->subject->name }}</h2>
                    <p>👨‍🏫 Pengajar: {{ $schedule->instructor->name ?? 'Tidak ada' }}</p>
                </div>

                @if($schedule->subject->materials && count($schedule->subject->materials) > 0)
                    <div class="materials-grid">
                        @foreach($schedule->subject->materials as $material)
                            <div class="material-card">
                                <div class="material-icon">
                                    @switch($material->type ?? 'document')
                                        @case('video')
                                            🎬
                                        @break
                                        @case('pdf')
                                            📄
                                        @break
                                        @case('image')
                                            🖼️
                                        @break
                                        @default
                                            📋
                                    @endswitch
                                </div>
                                <div class="material-title">{{ $material->title }}</div>
                                <div class="material-description">{{ Str::limit($material->description, 80) }}</div>
                                
                                <div class="material-meta">
                                    <span>📅 {{ \Carbon\Carbon::parse($material->created_at)->format('d M Y') }}</span>
                                    @if($material->file_url)
                                        <span>📦 {{ \Illuminate\Support\Str::afterLast($material->file_url, '.') }}</span>
                                    @endif
                                </div>

                                @if($material->file_url)
                                    <a href="{{ $material->file_url }}" target="_blank" class="download-btn">
                                        ⬇️ Download
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3>Belum ada materi</h3>
                        <p>Materi untuk mata pelajaran ini akan ditambahkan segera oleh pengajar</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h3>Anda belum memilih mata pelajaran</h3>
                <p>Pilih mata pelajaran terlebih dahulu untuk melihat materi yang tersedia</p>
                <a href="{{ route('student.pilih') }}" class="nav-btn nav-btn-primary" style="margin-top: 20px;">
                    Pilih Mata Pelajaran Sekarang
                </a>
            </div>
        @endforelse
    </div>
</body>
</html>