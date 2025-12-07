<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jadwal - Admin IP School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* Header & Buttons */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; text-decoration: none; color: white; display: inline-block;}
        .btn-back { background: #6b7280; }
        .btn-create { background: #10b981; }
        .btn-edit { background: #f59e0b; padding: 5px 10px; font-size: 12px; }
        .btn-delete { background: #ef4444; padding: 5px 10px; font-size: 12px; }

        /* Table */
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; font-weight: 600; color: #374151; }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .modal-content { background-color: #fff; margin: 5% auto; padding: 25px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .modal-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: 600; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        .row-time { display: flex; gap: 10px; }
        .row-time div { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-back">Kembali ke Dashboard</a>
            </div>
            <h1>Manajemen Jadwal</h1>
            <button onclick="openCreateModal()" class="btn btn-create">Buat Jadwal Baru</button>
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- SweetAlert messages will be shown by the script included at bottom --}}

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th>Jenjang</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $sched)
                    <tr>
                        <td><strong>{{ $sched->subject->name }}</strong></td>
                        <td>{{ $sched->instructor->fullname }}</td>
                        <td><span style="background:#eee; padding:2px 6px; border-radius:4px; font-weight:bold">{{ $sched->grade_level }}</span></td>
                        <td>{{ $sched->day }}</td>
                        <td>{{ substr($sched->time_start, 0, 5) }} - {{ substr($sched->time_end, 0, 5) }}</td>
                        <td>
                            <button class="btn btn-edit" onclick="openEditModal(
                                {{ $sched->id }},
                                '{{ $sched->subject->name }}',
                                '{{ $sched->instructor_id }}',
                                '{{ $sched->grade_level }}',
                                '{{ $sched->day }}',
                                '{{ $sched->time_start }}',
                                '{{ $sched->time_end }}'
                            )">
                                Edit
                            </button>

                            <form action="{{ route('admin.schedules.destroy', $sched->id) }}" method="POST" style="display:inline;" class="delete-schedule">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="createModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buat Mata Pelajaran & Jadwal Baru</h3>
                <span style="cursor:pointer; font-size:24px;" onclick="closeModal('createModal')">&times;</span>
            </div>
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Mata Pelajaran</label>
                    <input type="text" name="subject_name" placeholder="Contoh: Fotografi" required>
                </div>
                <div class="form-group">
                    <label>Guru Pengampu</label>
                    <select name="instructor_id" required>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Jenjang Kelas</label>
                    <select name="grade_level" id="edit_grade_level" required> 
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Hari</label>
                    <select name="day" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>
                <div class="row-time">
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="time_start" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="time_end" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-create" style="width:100%">Simpan</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Jadwal</h3>
                <span style="cursor:pointer; font-size:24px;" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama Mata Pelajaran</label>
                    <input type="text" name="subject_name" id="edit_subject_name" required>
                </div>
                <div class="form-group">
                    <label>Guru Pengampu</label>
                    <select name="instructor_id" id="edit_instructor_id" required>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Jenjang Kelas</label>
                    <select name="grade_level" id="edit_grade_level" required> 
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Hari</label>
                    <select name="day" id="edit_day" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>
                <div class="row-time">
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="time_start" id="edit_time_start" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="time_end" id="edit_time_end" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-create" style="width:100%">Update Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() { document.getElementById('createModal').style.display = 'block'; }
        
        function openEditModal(id, subjectName, instructorId, grade, day, start, end) {

            document.getElementById('editModal').style.display = 'block';
            
            document.getElementById('editForm').action = '/admin/edit/' + id;

            document.getElementById('edit_subject_name').value = subjectName;
            document.getElementById('edit_instructor_id').value = instructorId;
            document.getElementById('edit_grade_level').value = grade;
            document.getElementById('edit_day').value = day;
            document.getElementById('edit_time_start').value = start;
            document.getElementById('edit_time_end').value = end;
        }

        function closeModal(id) { document.getElementById(id).style.display = 'none'; }
        
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show session messages via SweetAlert2
        (function() {
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Jadwal Bentrok!',
                    text: {!! json_encode(session('error')) !!},
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!},
                    showConfirmButton: false,
                    timer: 2000,
                    toast: true
                });
            @endif
        })();

        // Delete confirmation for schedules
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form.delete-schedule').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin hapus jadwal ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>