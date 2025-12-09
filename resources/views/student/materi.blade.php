@extends('layouts.app')

@section('title', 'Materi Siswa')

@section('custom-styles')
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

.nav-btn-secondary {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.nav-btn-secondary:hover {
    background: #eff6ff;
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

.card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); max-width: 1100px; margin: 0 auto; }
@endsection

@section('content')

<div class="card">
    <h1 style="color: #111827; font-size: 28px; margin-bottom: 30px;">📚 Materi Siswa</h1>
    
    @forelse($enrolledSchedules as $schedule)
            <div class="subject-section">
                <div class="subject-header">
                    <h2>{{ $schedule->subject->name }}</h2>
                    <p>Pengajar: {{ $schedule->instructor->fullname ?? '-' }}</p>
                </div>

                @if(isset($schedule->materials) && count($schedule->materials) > 0)
                    <div class="materials-grid">
                        @foreach($schedule->materials as $material)
                            <div class="material-card">
                                <div class="material-icon">
                                    @php
                                        $ext = strtolower(pathinfo($material->file_path, PATHINFO_EXTENSION));
                                        $icon = match($ext) {
                                            'pdf' => '📄',
                                            'jpg', 'jpeg', 'png', 'gif' => '🖼️',
                                            'mp4', 'avi', 'mov' => '🎬',
                                            'doc', 'docx' => '📝',
                                            'xls', 'xlsx' => '📊',
                                            'ppt', 'pptx' => '🎯',
                                            default => '📋'
                                        };
                                    @endphp
                                    {{ $icon }}
                                </div>
                                <div class="material-title">{{ $material->title }}</div>
                                
                                <div class="material-meta">
                                    <span>📅 {{ Carbon::parse($material->created_at)->format('d M Y') }}</span>
                                    <span>{{ strtoupper($ext) }}</span>
                                </div>

                                <a href="{{ route('materials.show', $material->id) }}" class="download-btn" download>
                                    ⬇️ Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <h3>Belum ada materi</h3>
                        <p>Materi untuk mata pelajaran ini akan ditambahkan segera oleh pengajar</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <h3>Anda belum memilih mata pelajaran</h3>
                <p>Pilih mata pelajaran terlebih dahulu untuk melihat materi yang tersedia</p>
                <a href="{{ route('student.pilih') }}" class="nav-btn nav-btn-secondary" style="margin-top: 20px;">
                    Pilih Mata Pelajaran Sekarang
                </a>
            </div>
        @endforelse
    </div>
@endsection