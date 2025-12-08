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
        
        /* Button untuk Daftar Siswa */
        .btn-view-students {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-view-students:hover { background-color: #059669; }

        /* Modal Styles */
        .modal-overlay { 
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            bottom: 0; 
            background: rgba(0,0,0,0.5); 
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        
        .modal-content { 
            background: white; 
            border-radius: 8px; 
            padding: 24px; 
            width: 90%; 
            max-width: 600px; 
            max-height: 80vh; 
            overflow-y: auto; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2); 
        }
        
        .modal-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 16px; 
        }
        .modal-header h3 { margin: 0; color: #111827; font-size: 18px; }
        .modal-close { 
            background: none; 
            border: none; 
            font-size: 28px; 
            cursor: pointer; 
            color: #6b7280; 
            padding: 0;
            line-height: 1;
        }

        .modal-subject-info {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 4px;
            font-size: 13px;
            color: #1e40af;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .modal-table thead {
            background-color: #f3f4f6;
        }
        .modal-table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 12px;
        }
        .modal-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
            font-size: 13px;
        }

        .btn-kick-modal {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 11px;
            transition: background-color 0.2s;
        }
        .btn-kick-modal:hover { background-color: #dc2626; }

        .modal-empty {
            text-align: center;
            color: #9ca3af;
            padding: 20px;
            font-size: 14px;
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
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                        <div style="flex:1;">
                            <h3 style="margin:0 0 8px;">{{ $class->subject->name }}</h3>
                            <div class="class-info">
                                Hari: {{ $class->day }} &nbsp;|&nbsp; Jam: {{ $class->time_start }} - {{ $class->time_end }} &nbsp;|&nbsp; Kelas: {{ $class->grade_level }}
                            </div>
                        </div>
                        <button class="btn-view-students" type="button" data-class-id="{{ $class->id }}" data-class-name="{{ $class->subject->name }}" data-class-day="{{ $class->day }}" data-class-time="{{ substr($class->time_start, 0, 5) }}-{{ substr($class->time_end, 0, 5) }}" data-class-grade="{{ $class->grade_level }}" data-students="{{ json_encode($class->students->map(function($s){ return ['id' => $s->id, 'nis' => $s->nis, 'name' => $s->fullname, 'grade' => $s->class_grade]; })->values()) }}">
                            Lihat Siswa
                        </button>
                    </div>
                </div>
            @endforeach
        @endif

    </div>

    <!-- Modal untuk Daftar Siswa -->
    <div class="modal-overlay" id="studentsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Daftar Siswa</h3>
                <button class="modal-close" type="button" onclick="closeStudentsModal()">×</button>
            </div>
            
            <div id="modalStudentsList"></div>
        </div>
    </div>
    
    <script>
    function openStudentsModal(btn) {
        const className = $(btn).data('class-name');
        let students = $(btn).data('students');
        
        if (typeof students === 'string') {
            try {
                students = JSON.parse(students);
            } catch(e) {
                students = [];
            }
        }
        
        $('#modalTitle').text('Daftar Siswa - ' + className);
        
        // Build table
        let html = '';
        if (!students || students.length === 0) {
            html = '<p class="modal-empty">Belum ada siswa yang terdaftar pada mata pelajaran ini.</p>';
        } else {
            html = '<table class="modal-table"><thead><tr><th>Nama</th><th>NIS</th><th>Kelas</th><th style="text-align:center; width:70px;">Aksi</th></tr></thead><tbody>';
            students.forEach(s => {
                html += '<tr id="modal-student-' + s.id + '"><td>' + s.name + '</td><td>' + s.nis + '</td><td>' + s.grade + '</td><td style="text-align:center;"><button class="btn-kick-modal" type="button" data-student-id="' + s.id + '" data-nis="' + s.nis + '" data-class-id="' + $(btn).data('class-id') + '" data-student-name="' + s.name + '">Keluarkan</button></td></tr>';
            });
            html += '</tbody></table>';
        }
        
        $('#modalStudentsList').html(html);
        $('#studentsModal').addClass('active');
    }
    
    function closeStudentsModal() {
        $('#studentsModal').removeClass('active');
    }
    
    $(document).ready(function() {
        // Open modal when clicking view students button
        $('.btn-view-students').on('click', function() {
            openStudentsModal(this);
        });
        
        // Close modal when clicking outside
        $('#studentsModal').on('click', function(e) {
            if (e.target === this) {
                closeStudentsModal();
            }
        });
        
        // Handle kick student from modal
        $(document).on('click', '.btn-kick-modal', function(e) {
            e.preventDefault();
            const btn = $(this);
            const studentId = btn.data('student-id');
            const nis = btn.data('nis');
            const classId = btn.data('class-id');
            const studentName = btn.data('student-name');
            
            Swal.fire({
                title: 'Keluarkan Siswa?',
                text: 'Apakah Anda yakin ingin mengeluarkan ' + studentName + ' dari mata pelajaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Keluarkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("instructor.kick_student") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nis: nis,
                            class_id: classId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('#modal-student-' + studentId).fadeOut(300, function() {
                                    $(this).remove();
                                });
                            } else {
                                Swal.fire({icon: 'error', title: 'Gagal!', text: response.message});
                            }
                        },
                        error: function() {
                            Swal.fire({icon: 'error', title: 'Error!', text: 'Terjadi kesalahan koneksi.'});
                        }
                    });
                }
            });
        });
    });
    </script>

</body>
</html>