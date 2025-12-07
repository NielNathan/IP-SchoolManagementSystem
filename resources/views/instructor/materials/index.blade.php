@extends('layouts.app')

@section('title', 'Materi Saya')

@section('custom-styles')
.card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
h1 { color: white; font-size: 28px; margin-bottom: 30px; text-align: center; }
.btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
.btn-primary { background: #2563eb; color: white; }
.btn-primary:hover { background: #1e40af; }
.btn-warning { background: #f59e0b; color: white; }
.btn-warning:hover { background: #d97706; }
.btn-danger { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; }
.btn-group { display: flex; gap: 8px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background-color: #f9fafb; font-weight: 600; color: #374151; }
.alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
.alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5; }
.empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
.empty-state h3 { color: #111827; font-size: 18px; margin-bottom: 8px; }
@media (max-width: 600px) { .btn-group { flex-direction: column; } table { font-size: 12px; } th, td { padding: 8px; } }
@endsection

@section('content')
<h1>📚 Materi Saya</h1>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #111827; margin: 0;">Daftar Materi yang Saya Upload</h2>
        <a href="{{ route('materials.create') }}" class="btn btn-primary">
            ➕ Upload Materi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($materials->isEmpty())
        <div class="empty-state">
            <h3>Belum ada materi yang diupload</h3>
            <p>Mulai dengan mengupload materi baru menggunakan tombol di atas.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Judul Materi</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $material)
                <tr>
                    <td>{{ $material->title }}</td>
                    <td>{{ $material->subject->name ?? '-' }}</td>
                    <td>{{ $material->grade_level }}</td>
                    <td>{{ \Carbon\Carbon::parse($material->created_at)->format('d M Y H:i') }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('instructor.materials.edit', $material->id) }}" class="btn btn-warning">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('instructor.materials.delete', $material->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus materi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('instructor.dashboard') }}" class="btn btn-secondary" style="display: inline-block; padding: 10px 24px;">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
