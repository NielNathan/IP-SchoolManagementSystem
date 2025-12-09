@extends('layouts.app')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* Header & Stats */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card h3 { margin: 0; color: #6b7280; font-size: 14px; text-transform: uppercase; }
        .card .number { font-size: 32px; font-weight: 700; color: #111827; margin-top: 5px; }
        .card.highlight { border-left: 5px solid #2563eb; }

        /* Tables */
        .section-title { font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #374151; }
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; font-weight: 600; color: #374151; }
        .btn-view { background-color: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500; }
        .btn-view:hover { background-color: #2563eb; }

        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 25px; border: 1px solid #888; width: 90%; max-width: 550px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: slideDown 0.3s ease-out; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .modal-title { font-weight: 700; font-size: 18px; color: #1f2937; }
        .close-btn { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
        .close-btn:hover { color: #000; }
        
        .student-list-modal { list-style: none; padding: 0; margin: 0; max-height: 400px; overflow-y: auto; }
        /* Update Flex di LI agar tombol reset rapi */
        .student-list-modal li { padding: 10px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
        
        .grade-divider { background: #f3f4f6; color: #374151; font-weight: 700; font-size: 12px; padding: 8px 12px; margin-top: 15px; margin-bottom: 5px; border-radius: 6px; border-left: 4px solid #2563eb; letter-spacing: 0.5px; }
        .badge-kelas { background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-left: 5px; }
        
        /* STYLE BARU UNTUK FITUR RESET */
        .badge-status-final { background: #dcfce7; color: #166534; font-size: 10px; padding: 2px 6px; border-radius: 4px; border: 1px solid #bbf7d0; margin-left: 5px; }
        .badge-status-pending { background: #fef3c7; color: #92400e; font-size: 10px; padding: 2px 6px; border-radius: 4px; border: 1px solid #fde68a; margin-left: 5px; }
        
        .btn-reset { 
            background-color: #fee2e2; color: #991b1b; 
            border: 1px solid #fecaca; padding: 4px 8px; 
            border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 600; 
            transition: all 0.2s;
        }
        .btn-reset:hover { background-color: #ef4444; color: white; border-color: #ef4444; }

        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>
    @include('partials.header-home')

    <div class="container">
        
        {{-- Flash Message Admin --}}
        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        <div class="header">
            <h1>🛡️ Administrator Panel</h1>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.schedules.index') }}" style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13.3px; border: 1px solid #2563eb; justify-content: center; align-items: center; display: flex;">Edit Jadwal</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card highlight">
                <h3>Total Siswa</h3>
                <div class="number">{{ $totalSiswa }}</div>
            </div>
            <div class="card highlight">
                <h3>Total Guru</h3>
                <div class="number">{{ $totalGuru }}</div>
            </div>
            <div class="card highlight">
                <h3>Kelas Dibuka</h3>
                <div class="number">{{ $totalKelas }}</div>
            </div>
        </div>

        <h3 class="section-title">📋 Data Lengkap Semua Kelas</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="25%">Mata Pelajaran</th>
                        <th width="25%">Guru</th>
                        <th width="25%">Jumlah</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allClasses as $class)
                    <tr>
                        <td><strong>{{ $class->subject->name ?? '-' }}</strong></td>
                        <td>@foreach($class->multi_instructors as $guru)
                                {{ $guru->fullname }}<br>
                            @endforeach
                        </td>
                        <td>{{ count($class->multi_students) }} Siswa</td>
                        <td><button class="btn-view" onclick="openModal('modal-{{ $class->id }}')">Lihat Siswa</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach($allClasses as $class)
    <div id="modal-{{ $class->id }}" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">{{ $class->subject->name ?? 'Detail' }}</div>
                <span class="close-btn" onclick="closeModal('modal-{{ $class->id }}')">&times;</span>
            </div>

            <ul class="student-list-modal">
                @if($class->multi_students->isEmpty())
                    <li style="justify-content: center; color: #9ca3af; font-style: italic;">Belum ada siswa di kelas ini.</li>
                @else
                    <?php
                        $groupedStudents = $class->multi_students->sortBy('class_grade')->groupBy(function($s) {
                            return explode('-', $s->class_grade)[0];
                        });
                    ?>
                    @foreach($groupedStudents as $level => $students)
                        <div class="grade-divider">SISWA KELAS {{ $level }}</div>
                        @foreach($students as $student)
                            <li>
                                {{-- Kolom Nama & Info --}}
                                <div style="display: flex; flex-direction: column;">
                                    <span>
                                        {{ $student->fullname }} 
                                        <small style="color:#888">({{ $student->nis ?? 'NIS' }})</small>
                                    </span>
                                    
                                    {{-- Indikator Status & Kelas --}}
                                    <div style="margin-top: 2px;">
                                        <span class="badge-kelas">{{ $student->class_grade }}</span>
                                        @if($student->is_schedule_finalized)
                                            <span class="badge-status-final">🔒 Final</span>
                                        @else
                                            <span class="badge-status-pending">✎ Draft</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Tombol Reset (Hanya muncul jika sudah final) --}}
                                {{-- Jika admin ingin reset kapan saja, hilangkan if-nya --}}
                                @if($student->is_schedule_finalized)
                                    <form action="{{ route('admin.student.reset', $student->id) }}" method="POST" onsubmit="return confirm('Reset status jadwal siswa {{ $student->fullname }}? Siswa akan bisa mengambil/menghapus mapel lagi.');">
                                        @csrf
                                        <button type="submit" class="btn-reset" title="Buka Kunci Jadwal">
                                            🔓 Reset
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    @endforeach

    <script>
        function openModal(id) { document.getElementById(id).style.display = "block"; document.body.style.overflow = "hidden"; }
        function closeModal(id) { document.getElementById(id).style.display = "none"; document.body.style.overflow = "auto"; }
        window.onclick = function(e) { if (e.target.classList.contains('modal')) { e.target.style.display = "none"; document.body.style.overflow = "auto"; } }
    </script>
</body>
</html>