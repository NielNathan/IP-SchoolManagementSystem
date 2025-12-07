<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. BERSIHKAN DATABASE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Student::truncate();
        Instructor::truncate();
        Subject::truncate();
        Schedule::truncate();
        // Pastikan nama tabel pivot benar (cek di migration kamu, biasanya schedule_student)
        DB::table('enrollments')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // 2. BUAT USER GURU
        // ==========================================
        $teachers = [
            ['name' => 'Pak Budi', 'email' => 'budi@guru.com', 'nip' => '198001', 'full' => 'Budi Santoso, S.Pd'],
            ['name' => 'Bu Siti', 'email' => 'siti@guru.com', 'nip' => '198502', 'full' => 'Siti Aminah, M.Pd'],
            ['name' => 'Pak Joko', 'email' => 'joko@guru.com', 'nip' => '199003', 'full' => 'Joko Anwar, S.Kom'],
        ];

        $teacherIds = [];
        foreach ($teachers as $t) {
            $user = User::create([
                'name' => $t['name'],
                'email' => $t['email'],
                'password' => Hash::make('teacher123'),
                'role' => 'teacher'
            ]);
            $inst = Instructor::create([
                'user_id' => $user->id,
                'nip' => $t['nip'],
                'fullname' => $t['full']
            ]);
            $teacherIds[] = $inst->id;
        }

        // ==========================================
        // 3. BUAT USER SISWA
        // ==========================================
        
        // Siswa 1: Danelle (Kelas 11)
        $userDanelle = User::create([
            'name' => 'Danelle',
            'email' => 'danelle@school.com',
            'password' => Hash::make('danel123'),
            'role' => 'student'
        ]);
        $siswaDanelle = Student::create([
            'user_id' => $userDanelle->id,
            'nis' => '20250001',
            'fullname' => 'Roland Danelle Guritno',
            'class_grade' => 'XI-1'
        ]);

        // Siswa 2: James (Kelas 10)
        $userJames = User::create([
            'name' => 'James',
            'email' => 'james@school.com',
            'password' => Hash::make('james123'),
            'role' => 'student'
        ]);
        $siswaJames = Student::create([
            'user_id' => $userJames->id,
            'nis' => '20250002',
            'fullname' => 'James Albert Forisa',
            'class_grade' => 'X-2'
        ]);

        $allStudents = [$siswaDanelle, $siswaJames];

        // ==========================================
        // 4. BUAT JADWAL & AUTO ASSIGN
        // ==========================================
        $jenjangs = ['X', 'XI', 'XII'];
        
        $mapelWajib = ['Pendidikan Agama dan Budi Pekerti', 'Bahasa Indonesia', 'Bahasa Inggris', 'Matematika (Wajib)', 'PJOK'];
        $mapelPilihan = ['Biologi', 'Fisika', 'Ekonomi', 'Sosiologi'];

        foreach ($jenjangs as $jenjang) {
            
            // --- A. LOOP MAPEL WAJIB (AUTO ASSIGN) ---
            foreach ($mapelWajib as $m) {
                $sub = Subject::firstOrCreate(['name' => $m]);

                $sched = Schedule::create([
                    'subject_id' => $sub->id,
                    'instructor_id' => $teacherIds[array_rand($teacherIds)],
                    'grade_level' => $jenjang, 
                    'day' => 'Senin',
                    'time_start' => '07:00:00',
                    'time_end' => '09:00:00',
                    'quota' => 40
                ]);

                // *** LOGIKA PERBAIKAN DI SINI ***
                foreach ($allStudents as $stu) {
                    // 1. Pecah "XI-1" menjadi ["XI", "1"]
                    $parts = explode('-', $stu->class_grade);
                    $gradeOnly = $parts[0]; // Ambil "XI" saja

                    // 2. Bandingkan PERSIS (===)
                    // Jadi "XI" tidak akan dianggap sama dengan "X"
                    if ($gradeOnly === $jenjang) {
                        $stu->schedules()->syncWithoutDetaching([$sched->id]);
                    }
                }
            }

            // --- B. LOOP MAPEL PILIHAN (JANGAN AUTO ASSIGN) ---
            foreach ($mapelPilihan as $m) {
                $sub = Subject::firstOrCreate(['name' => $m]);
                
                Schedule::create([
                    'subject_id' => $sub->id,
                    'instructor_id' => $teacherIds[array_rand($teacherIds)],
                    'grade_level' => $jenjang, 
                    'day' => 'Selasa',
                    'time_start' => '10:00:00',
                    'time_end' => '12:00:00',
                    'quota' => 30
                ]);
            }
        }

        // BUAT USER ADMIN
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@school.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin' 
        ]);
    }
}