<!DOCTYPE html>
<html>
<head><title>SKS Sistem</title></head>
<body>
    <h1>Halo, {{ $student->fullname }}</h1>
    <p>NIS: {{ $student->nis }} | Kelas: {{ $student->class_grade }}</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <hr>

    @if(session('success')) <p style="color:green">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color:red">{{ session('error') }}</p> @endif

    <h3>Jadwal Saya:</h3>
    <ul>
        @forelse($mySchedules as $my)
            <li>{{ $my->subject->name }} - {{ $my->day }} ({{ $my->time }})</li>
        @empty
            <li>Belum ambil SKS.</li>
        @endforelse
    </ul>

    <hr>

    <h3>Daftar Kelas Tersedia:</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Mapel</th>
            <th>Guru</th>
            <th>Jadwal</th>
            <th>Aksi</th>
        </tr>
        @foreach($allSchedules as $sched)
        <tr>
            <td>{{ $sched->subject->name }}</td>
            <td>{{ $sched->instructor->fullname }}</td>
            <td>{{ $sched->day }}, {{ $sched->time }}</td>
            <td>
                <form action="{{ route('student.enroll') }}" method="POST">
                    @csrf <input type="hidden" name="schedule_id" value="{{ $sched->id }}">
                    <button type="submit">Ambil</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>