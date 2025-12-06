@extends('layouts.app')

@section('title', 'Pilih Mata Pelajaran')

@section('custom-styles')
h3 { color: #374151; margin-top: 30px; }

table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background-color: #f9fafb; font-weight: 600; color: #374151; }

.badge-wajib { background: #dbeafe; color: #1e40af; font-size: 11px; padding: 2px 8px; border-radius: 99px; font-weight: 600; margin-left: 8px; }
.btn-action { padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 500; color: white; }
.btn-delete { background-color: #ef4444; }
.btn-add { background-color: #2563eb; }
.btn-disabled { background-color: #9ca3af; cursor: not-allowed; }

.info-box { background: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 8px; color: #1e3a8a; margin-bottom: 20px; }
.card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto; }
@endsection

@section('content')

<div class="card">

        <div class="info-box">
            <strong>Total Mata Pelajaran: {{ $totalTaken }} / 12</strong>
            @if($totalTaken >= 12)
                <br><span style="color: #dc2626; font-weight: bold;">Jadwal Penuh! Hapus mapel pilihan jika ingin mengganti.</span>
            @endif
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                {{ session('error') }}
            </div>
        @endif

        <h3>📚 Jadwal Saya</h3>
        @if($mySchedules->isEmpty())
            <p style="color: #6b7280; font-style: italic;">Belum ada kelas yang diambil.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mySchedules as $my)
                    <tr>
                        <td>
                            {{ $my->subject->name }}
                            @if(in_array($my->subject->name, $wajibTaken))
                                <span class="badge-wajib">Wajib</span>
                            @endif
                        </td>
                        <td>{{ $my->instructor->fullname }}</td>
                        <td>{{ $my->day }}, 
                            {{ substr($my->time_start, 0, 5) }} - {{ substr($my->time_end, 0, 5) }}
                        </td>
                        <td>
                            @if(in_array($my->subject->name, $wajibTaken))
                                <span style="color: #9ca3af; font-size: 12px; font-style: italic;">🔒 Terkunci</span>
                            @else
                                <form action="{{ route('student.unenroll') }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan mapel ini?');">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $my->id }}">
                                    <button type="submit" class="btn-action btn-delete">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h3>📝 Daftar Kelas Pilihan Tersedia</h3>
        <table>
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allSchedules as $sched)
                <tr>
                    <td>{{ $sched->subject->name }}</td>
                    <td>{{ $sched->instructor->fullname }}</td>
                    <td>{{ $sched->day }}, 
                        {{ substr($sched->time_start, 0, 5) }} - {{ substr($sched->time_end, 0, 5) }}
                    </td>
                    <td>
                        <form action="{{ route('student.enroll') }}" method="POST">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $sched->id }}">
                            
                            @if($totalTaken >= 12)
                                <button type="button" class="btn-action btn-disabled" disabled>Penuh</button>
                            @else
                                <button type="submit" class="btn-action btn-add">Ambil</button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6b7280; padding: 20px;">
                        Tidak ada kelas pilihan lain yang tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

</div>
@endsection