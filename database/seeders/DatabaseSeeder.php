<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Tambahkan ini biar aman

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Student::truncate();
        Instructor::truncate();
        Subject::truncate();
        Schedule::truncate();
        DB::table('enrollments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // 1. BUAT USER GURU
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
        // 2. BUAT USER SISWA
        // ==========================================
        
        // Siswa 1: Danelle
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

        // Siswa 2: James
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
        // 3. DAFTAR MATA PELAJARAN
        // ==========================================
        $jenjangs = ['X', 'XI', 'XII'];
        
        $mapelWajib = ['Pendidikan Agama', 'Bahasa Indonesia', 'Bahasa Inggris', 'Matematika (Wajib)', 'PJOK'];
        $mapelPilihan = ['Biologi', 'Fisika', 'Ekonomi', 'Sosiologi'];

        foreach ($jenjangs as $jenjang) {
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

                foreach ($allStudents as $stu) {
                    if (str_contains($stu->class_grade, $jenjang)) {
                        $stu->schedules()->syncWithoutDetaching([$sched->id]);
                    }
                }
            }

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