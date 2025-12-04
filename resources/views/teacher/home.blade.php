<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Guru - IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        /* Header Style */
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { margin: 0; color: #111827; font-size: 24px; }
        p.subtitle { color: #6b7280; margin: 5px 0 0; font-size: 14px; }
        
        /* Logout Button */
        .logout-btn { background-color: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-family: inherit; }
        .logout-btn:hover { background-color: #dc2626; }

        /* Class Card Style */
        .class-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 20px; background-color: #fff; }
        .class-card h3 { margin: 0 0 10px; color: #2563eb; }
        .class-info { background: #f9fafb; padding: 10px; border-radius: 6px; font-size: 14px; color: #374151; font-weight: 600; margin-bottom: 15px; }
        
        /* Student List */
        .student-list { margin: 0; padding-left: 20px; }
        .student-list li { margin-bottom: 5px; color: #4b5563; }
        .empty-msg { color: #9ca3af; font-style: italic; }
    </style>
</head>
<body>
    @include('partials.header-home')

    <div class="container">
        <div class="header-section">
            <div>
                <h1>Halo, {{ $instructor->fullname }}</h1>
                <p class="subtitle">NIP: {{ $instructor->nip }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 20px 0;">

        <h2 style="color: #374151;">Jadwal Mengajar & Daftar Presensi</h2>

        @if($myClasses->isEmpty())
            <p class="empty-msg">Anda tidak memiliki jadwal mengajar semester ini.</p>
        @else
            @foreach($myClasses as $class)
                <div class="class-card">
                    <h3>Mata Pelajaran: {{ $class->subject->name }}</h3>
                    
                    <div class="class-info">
                        Hari: {{ $class->day }} &nbsp;|&nbsp; 
                        Jam: {{ substr($class->time_start, 0, 5) }} - {{ substr($class->time_end, 0, 5) }}
                    </div>

                    <strong>Daftar Siswa ({{ $class->students->count() }} Siswa):</strong>
                    <br><br>

                    @if($class->students->isEmpty())
                        <p class="empty-msg">Belum ada siswa yang mengambil kelas ini.</p>
                    @else
                        <ol class="student-list">
                            @foreach($class->students as $student)
                                <li>
                                    <strong>{{ $student->fullname }}</strong> 
                                    (NIS: {{ $student->nis }}) - Kelas {{ $student->class_grade }}
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            @endforeach
        @endif

    </div>

</body>
</html>