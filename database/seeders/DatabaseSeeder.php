<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. DATA SUBJECTS (Mata Pelajaran Kurikulum Merdeka)
        // ==========================================
        $subjects = [
            ['name' => 'Matematika Tingkat Lanjut', 'sks' => 5],
            ['name' => 'Fisika Kuantum', 'sks' => 4],
            ['name' => 'Kimia Organik', 'sks' => 4],
            ['name' => 'Biologi Sel', 'sks' => 4],
            ['name' => 'Ekonomi Makro', 'sks' => 3],
            ['name' => 'Sosiologi Perkotaan', 'sks' => 3],
            ['name' => 'Bahasa Jepang', 'sks' => 2],
            ['name' => 'Informatika & Coding', 'sks' => 5],
        ];

        foreach ($subjects as $sub) {
            Subject::create($sub);
        }

        // ==========================================
        // 2. DATA INSTRUCTORS (GURU) + USER LOGINNYA
        // ==========================================
        $guruData = [
            ['name' => 'Pak Budi', 'email' => 'budi@guru.com', 'nip' => '1980001', 'full' => 'Budi Santoso, S.Pd'],
            ['name' => 'Bu Siti', 'email' => 'siti@guru.com', 'nip' => '1985002', 'full' => 'Siti Aminah, M.Si'],
            ['name' => 'Pak Joko', 'email' => 'joko@guru.com', 'nip' => '1990003', 'full' => 'Joko Anwar, M.Kom'],
        ];

        foreach ($guruData as $g) {
            // Buat Akun Login
            $user = User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => Hash::make('password'), // Password default: 'password'
                'role' => 'teacher'
            ]);

            // Buat Profil Guru
            Instructor::create([
                'user_id' => $user->id,
                'nip' => $g['nip'],
                'fullname' => $g['full']
            ]);
        }

        // ==========================================
        // 3. DATA STUDENTS (SISWA) + USER LOGINNYA
        // ==========================================
        // Kita buat Danelle sebagai user utama testing
        $danelleUser = User::create([
            'name' => 'Danelle',
            'email' => 'danelle@school.com',
            'password' => Hash::make('password'),
            'role' => 'student'
        ]);
        
        $danelleProfile = Student::create([
            'user_id' => $danelleUser->id,
            'nis' => '20250001',
            'fullname' => 'Danelle The Explorer',
            'class_grade' => 'XII MIPA 1'
        ]);

        // Tambah 1 Siswa lagi buat temen
        $alexUser = User::create([
            'name' => 'Alex',
            'email' => 'alex@school.com',
            'password' => Hash::make('password'),
            'role' => 'student'
        ]);
        Student::create([
            'user_id' => $alexUser->id,
            'nis' => '20250002',
            'fullname' => 'Alex Ferguso',
            'class_grade' => 'XII IPS 2'
        ]);

        // ==========================================
        // 4. DATA SCHEDULES (JADWAL)
        // ==========================================
        // Kita ambil ID Subject dan Instructor secara acak/urut
        
        Schedule::create([
            'subject_id' => 1, // Matematika
            'instructor_id' => 1, // Pak Budi
            'day' => 'Senin',
            'time' => '07:00 - 09:30',
            'quota' => 30
        ]);

        Schedule::create([
            'subject_id' => 2, // Fisika
            'instructor_id' => 2, // Bu Siti
            'day' => 'Senin',
            'time' => '10:00 - 12:30',
            'quota' => 25
        ]);

        Schedule::create([
            'subject_id' => 8, // Informatika
            'instructor_id' => 3, // Pak Joko
            'day' => 'Selasa',
            'time' => '08:00 - 11:00',
            'quota' => 20
        ]);

        Schedule::create([
            'subject_id' => 5, // Ekonomi
            'instructor_id' => 1, // Pak Budi (Ngapal 2 mapel ceritanya)
            'day' => 'Rabu',
            'time' => '07:00 - 09:00',
            'quota' => 35
        ]);

        // ==========================================
        // 5. ISI DATA AWAL (Danelle ambil Matematika)
        // ==========================================
        $danelleProfile->schedules()->attach(1); // ID Jadwal Matematika
    }
}