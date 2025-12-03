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
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logout-btn { background-color: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        
        /* Stats Cards */
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
        
        /* List Siswa di dalam tabel */
        .student-list { margin: 0; padding-left: 20px; font-size: 13px; color: #4b5563; }
    </style>
</head>
<body>
    @include('partials.header-home')

    <div class="container">
        <div class="header">
            <h1>🛡️ Administrator Panel</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
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

        <div class="table-container" style="margin-bottom: 30px;">
            <table>
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <th>Jumlah Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topClasses as $index => $class)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td style="font-weight: bold; color: #2563eb;">{{ $class->subject->name }}</td>
                        <td>{{ $class->instructor->fullname }}</td>
                        <td>
                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 20px; font-weight: 600; font-size: 12px;">
                                {{ $class->students_count }} Siswa
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h3 class="section-title">Data Lengkap Semua Kelas</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="20%">Mata Pelajaran</th>
                        <th width="20%">Guru</th>
                        <th width="20%">Waktu</th>
                        <th width="40%">Daftar Siswa Mengambil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allClasses as $class)
                    <tr>
                        <td><strong>{{ $class->subject->name }}</strong></td>
                        <td>{{ $class->instructor->fullname }}</td>
                        <td>{{ $class->day }}, {{ $class->time }}</td>
                        <td>
                            @if($class->students->isEmpty())
                                <span style="color: #9ca3af; font-style: italic;">Kosong</span>
                            @else
                                <ul class="student-list">
                                    @foreach($class->students as $student)
                                        <li>{{ $student->fullname }} ({{ $student->class_grade }})</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>