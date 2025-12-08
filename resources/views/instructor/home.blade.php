<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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
        
        /* Container Tombol Header */
        .header-actions { display: flex; gap: 10px; }

        /* Upload Button (Biru) */
        .upload-btn { 
            background-color: #2563eb; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s;
        }
        .upload-btn:hover { background-color: #1d4ed8; }

        /* Class Card Style */
        .class-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 20px; background-color: #fff; }
        .class-card h3 { margin: 0 0 10px; color: #2563eb; }
        .class-info { background: #f9fafb; padding: 10px; border-radius: 6px; font-size: 14px; color: #374151; font-weight: 600; margin-bottom: 15px; }
        
        /* Student List */
        .student-list { margin: 0; padding-left: 20px; }
        .student-list li { margin-bottom: 5px; color: #4b5563; }
        .empty-msg { color: #9ca3af; font-style: italic; }

        /* Student List Styling */
        .student-list li {
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 8px; 
            padding-right: 10px; 
        }

        /* Kick Button Styling */
        .btn-kick { 
            background-color: #ef4444; 
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px; 
            margin-left: 15px; 
            transition: background-color 0.2s;
            flex-shrink: 0; 
        }

        .btn-kick:hover {
            background-color: #dc2626;
        }
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
            
            <div class="header-actions">
                <a href="{{ route('instructor.materials.index') }}" class="upload-btn" style="background-color: #764ba2;">
                    📚 Kelola Materi
                </a>
                <a href="{{ route('materials.create') }}" class="upload-btn">
                    📤 Upload Materi
                </a>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 20px 0;">

        <h2 style="color: #374151;">Jadwal Mengajar & Daftar Presensi</h2>

        @if($myClasses->isEmpty())
            <p class="empty-msg">Anda tidak memiliki jadwal mengajar semester ini.</p>
        @else
            @foreach($myClasses as $class)
                <div class="class-card">
                    <h3>{{ $class->subject->name }}</h3>
                    
                    <div class="class-info">
                        Hari: {{ $class->day }} &nbsp;|&nbsp; Jam: {{ $class->time_start }} - {{ $class->time_end }} &nbsp;|&nbsp; Kelas: {{ $class->grade_level }}
                    </div>

                    <strong>Daftar Siswa ({{ $class->students->count() }} Siswa):</strong>
                    <br><br>

                    @if($class->students->isEmpty())
                        <p class="empty-msg">Belum ada siswa yang mengambil kelas ini.</p>
                    @else
                        <ol class="student-list">
                            @foreach($class->students as $student)
                                <li id="student-row-{{ $student->nis }}-{{ $class->id }}"> 
                                    
                                    <span>
                                        <strong>{{ $student->fullname }}</strong> 
                                        (NIS: {{ $student->nis }}) - Kelas {{ $student->class_grade }}
                                    </span>
                                    
                                    <button
                                        type="button"
                                        class="btn-kick"
                                        data-nis="{{ $student->nis }}"
                                        data-class-id="{{ $class->id }}"
                                        data-student-name="{{ $student->fullname }}"
                                    >
                                        Keluarkan
                                    </button>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            @endforeach
        @endif

    </div>
    
    <script>
    $(document).ready(function() {
        $('.btn-kick').on('click', function() {
            var button = $(this);
            var nis = button.data('nis');
            var classId = button.data('class-id');
            var studentName = button.data('student-name');
            
            // SweetAlert Konfirmasi
            Swal.fire({
                title: 'Konfirmasi Pengeluaran Siswa',
                html: 'Apakah Anda yakin ingin mengeluarkan **' + studentName + '** (NIS: ' + nis + ') dari mata pelajaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3741',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Keluarkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // Loading State
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    
                    button.prop('disabled', true);
                    
                    // AJAX Request
                    $.ajax({
                        url: '{{ route('instructor.kick_student') }}', 
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nis: nis,
                            class_id: classId 
                        },
                        success: function(response) {
                            Swal.close(); 
                            button.prop('disabled', false);
                            
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message || studentName + ' berhasil dikeluarkan!'
                                });
                                
                                $('#student-row-' + nis + '-' + classId).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan.'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();
                            button.prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan koneksi atau server.'
                            });
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>