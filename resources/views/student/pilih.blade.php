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
    .btn-locked { background-color: #e5e7eb; color: #6b7280; cursor: not-allowed; border: 1px solid #d1d5db; }

    .info-box { background: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 8px; color: #1e3a8a; margin-bottom: 20px; }
    .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto; }

    .submit-section { margin-top: 40px; padding: 20px; border-radius: 8px; text-align: center; }
    .submit-success { background: #f0fdf4; border: 1px solid #22c55e; color: #15803d; }
    
    .btn-final {
        background-color: #16a34a; color: white; font-size: 16px; 
        padding: 12px 24px; border: none; border-radius: 8px; 
        font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        transition: background 0.2s;
    }
    .btn-final:hover { background-color: #15803d; }
@endsection

@section('content')

@php
    $student = auth()->user()->student;
    $isFinal = $student->is_schedule_finalized;
@endphp

<div class="card">

    <div class="info-box">
        <strong>Total Mata Pelajaran: {{ $totalTaken }} / 12</strong>
        @if(session('warning_full'))
            <br>
            <span style="color: #dc2626; font-weight: bold; animation: fadeIn 0.5s;">
                Jadwal Penuh! Hapus mapel pilihan jika ingin mengganti.
            </span>
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
                        @if($isFinal)
                            <span style="color: #059669; font-size: 12px; font-weight: 600;">✓ Terambil</span>
                        @elseif(in_array($my->subject->name, $wajibTaken))
                            <span style="color: #9ca3af; font-size: 12px; font-style: italic;">🔒 Wajib</span>
                            @else
                                <form action="{{ route('student.unenroll') }}" method="POST" class="unenroll-form">
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

    @if(!$isFinal)
    <div class="submit-section submit-warning">
        <form action="{{ route('student.schedule.finalize') }}" method="POST" class="finalize-form">
            @csrf
            <button type="submit" class="btn-final">
                🔒 Submit & Finalisasi Jadwal
            </button>
        </form>
    </div>

    @elseif(session('success_finalized'))
        <div class="submit-section submit-success">
            <h3 style="margin: 0; color: #15803d;">Jadwal Telah Disimpan</h3>
            <p style="margin: 10px 0 0 0;">
                Anda telah mensubmit rencana studi Anda. <br>
                Silakan hubungi admin jika ada perubahan.
            </p>
        </div>
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
                    @if($isFinal)
                         <button type="button" class="btn-action btn-locked" disabled>🔒 Terkunci</button>
                    @else
                        <form action="{{ route('student.enroll') }}" method="POST" class="enroll-form">
                            @csrf
                            <input type="hidden" name="schedule_id" value="{{ $sched->id }}">
                            
                            @if($totalTaken >= 12)
                                <button type="button" class="btn-action btn-disabled" disabled>Penuh</button>
                            @else
                                <button type="submit" class="btn-action btn-add">Ambil</button>
                            @endif
                        </form>
                    @endif
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('form.finalize-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Jadwal akan dikunci permanen',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, kunci sekarang',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result){
                        if(result.isConfirmed){
                            Swal.fire({ title: 'Sedang memproses...', allowOutsideClick:false, didOpen: () => { Swal.showLoading(); } });
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('form.enroll-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    var btn = form.querySelector('button[type=submit]');
                    if(btn && btn.disabled) return e.preventDefault();
                    e.preventDefault();
                    Swal.fire({
                        title: 'Ambil Mata Pelajaran?',
                        text: 'Anda akan menambahkan mata pelajaran ini ke jadwal Anda.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ambil',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result){
                        if(result.isConfirmed){
                            Swal.fire({ title: 'Sedang memproses...', allowOutsideClick:false, didOpen: () => { Swal.showLoading(); } });
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('form.unenroll-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin membatalkan?',
                        text: 'Mapel ini akan dihapus dari jadwal Anda.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result){
                        if(result.isConfirmed){
                            Swal.fire({ title: 'Sedang memproses...', allowOutsideClick:false, didOpen: () => { Swal.showLoading(); } });
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection