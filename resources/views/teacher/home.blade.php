<?php

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Guru</title>
</head>
<body>

    <h1>Halo, Bapak/Ibu {{ $instructor->first_name }} {{ $instructor->last_name }}</h1>
    <p>NIP: {{ $instructor->nip }}</p>
    <hr>

    <h3>Jadwal Mengajar & Daftar Siswa</h3>

    @if($myClasses->isEmpty())
        <p>Anda tidak memiliki jadwal mengajar semester ini.</p>
    @else
        @foreach($myClasses as $class)
            <div style="border: 1px solid black; padding: 10px; margin-bottom: 20px;">
                <h4>
                    Mata Pelajaran: {{ $class->subject->name }} <br>
                    <small>Hari: {{ $class->day }} | Jam: {{ $class->time_start }} | Ruang: {{ $class->room }}</small>
                </h4>
                
                <strong>Daftar Presensi Siswa ({{ $class->students->count() }} Siswa):</strong>
                
                @if($class->students->isEmpty())
                    <p><i>Belum ada siswa yang mengambil kelas ini.</i></p>
                @else
                    <ol>
                        @foreach($class->students as $student)
                            <li>
                                {{ $student->first_name }} {{ $student->last_name }} 
                                (NIS: {{ $student->nis }}) - Kelas {{ $student->class_grade }}
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        @endforeach
    @endif

</body>
</html>