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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Student::truncate();
        Instructor::truncate();
        Subject::truncate();
        Schedule::truncate();
        DB::table('enrollments')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //  DATA GURU
        $teacherData = [
            ['Pak Budi', '198001', 'Budi Santoso, S.Pd', ['Matematika (Wajib)', 'Matematika (Peminatan)']],
            ['Bu Siti', '198502', 'Siti Aminah, M.Pd', ['Bahasa Indonesia', 'Sastra Indonesia']],
            ['Pak Joko', '199003', 'Joko Anwar, S.Kom', ['Informatika', 'Prakarya']],
            ['Pak John', '199204', 'Jonny Junu, B.Ed', ['Bahasa Inggris', 'Sastra Inggris']],
            ['Bu Mega', '198805', 'Mega Pertiwi, S.Si', ['Biologi', 'Kimia']],
            ['Pak Albertus', '197506', 'Albertus, M.Sc', ['Fisika']],
            ['Pak Agung', '198207', 'Agung Wicaksana, S.Hum', ['Sejarah Indonesia', 'Sejarah Peminatan']],
            ['Bu Sri', '198908', 'Sri Mulyani, S.E', ['Ekonomi', 'Akuntansi']],
            ['Pak Yul', '197009', 'Yulianto Surya, S.Ag', ['Pendidikan Agama dan Budi Pekerti']],
            ['Pak Raga', '199510', 'Raga Angga, S.Or', ['PJOK']],
            ['Bu Wahyu', '198711', 'Wahyu Putri, S.Sos', ['Sosiologi', 'Geografi']],
            ['Bu Citra', '199112', 'Citra Kirana, S.Sn', ['Seni Budaya']],
            ['Pak Sandy', '198313', 'Sandy Argita, S.H', ['Pendidikan Pancasila dan Kewarganegaraan']],
        ];

        $instructorsBySubject = [];

        foreach ($teacherData as $idx => $t) {
            $user = User::create([
                'name' => $t[0],
                'email' => strtolower(str_replace(['.', ' '], '', $t[0])) . '@school.com',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher'
            ]);

            $instructor = Instructor::create([
                'user_id' => $user->id,
                'nip' => $t[1],
                'fullname' => $t[2]
            ]);

            foreach ($t[3] as $mapelName) {
                $instructorsBySubject[$mapelName][] = $instructor->id;
            }
        }

        // DATA SISWA
        $studentsData = [
            ['James', 'james@school.com', '2025001', 'James Albert Forisa', 'X-1'],
            ['Danelle', 'danelle@school.com', '2024002', 'Roland Danelle Guritno', 'XI-2'],
            ['Nathan', 'nathaniel@school.com', '2023003', 'Nathaniel Davis Saputra', 'XII-3'], 
        ];

        $allStudentObjects = [];

        foreach ($studentsData as $s) {
            $u = User::create([
                'name' => $s[0],
                'email' => $s[1],
                'password' => Hash::make('student123'),
                'role' => 'student'
            ]);
            
            $stu = Student::create([
                'user_id' => $u->id,
                'nis' => $s[2],
                'fullname' => $s[3],
                'class_grade' => $s[4]
            ]);

            $allStudentObjects[] = $stu;
        }

        // DATA JADWAL
        $subjectsWajib = [
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Matematika (Wajib)',
            'Sejarah Indonesia',
            'Bahasa Inggris',
            'Seni Budaya',
            'PJOK',
            'PKWU'
        ];

        $subjectsPilihan = [
            'Matematika (Peminatan)', 'Biologi', 'Fisika', 'Kimia',
            'Geografi', 'Sejarah Peminatan', 'Sosiologi', 'Ekonomi',
            'Sastra Inggris', 'Informatika'
        ];

        // Definisi Jenjang
        $jenjangs = ['X', 'XI', 'XII'];
        
        // Definisi Hari & Slot Waktu
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeSlots = [
            ['07:00:00', '09:00:00'], 
            ['09:15:00', '11:15:00'], 
            ['12:00:00', '14:00:00'], 
            ['14:15:00', '16:15:00'], 
        ];

        $busyTeachers = [];

        foreach ($jenjangs as $jenjang) {
            $allMapel = array_merge($subjectsWajib, $subjectsPilihan);
            
            shuffle($allMapel); 
            
            $dayIndex = 0;
            $slotIndex = 0;

            foreach ($allMapel as $mapelName) {
                
                // 1. Create Subject (FirstOrCreate)
                $subjectDB = Subject::firstOrCreate(['name' => $mapelName]);

                // 2. Cari Guru
                $availableInstructors = $instructorsBySubject[$mapelName] ?? [];
                
                if (empty($availableInstructors)) {
                    $instructorId = Instructor::first()->id;
                } else {
                    $instructorId = null;
                    
                    // Cek guru yang kosong di jam ini
                    foreach ($availableInstructors as $candidateId) {
                        $currentDay = $days[$dayIndex];
                        $currentTime = $timeSlots[$slotIndex][0];

                        if (!isset($busyTeachers[$candidateId][$currentDay][$currentTime])) {
                            $instructorId = $candidateId;
                            break;
                        }
                    }
                    // Jika semua sibuk, ambil random
                    if (!$instructorId) {
                        $instructorId = $availableInstructors[array_rand($availableInstructors)];
                    }
                }

                // 3. Simpan Jadwal
                $sched = Schedule::create([
                    'subject_id' => $subjectDB->id,
                    'instructor_id' => $instructorId,
                    'grade_level' => $jenjang,
                    'day' => $days[$dayIndex],
                    'time_start' => $timeSlots[$slotIndex][0],
                    'time_end' => $timeSlots[$slotIndex][1],
                    'quota' => 30
                ]);

                // Tandai guru sibuk
                $busyTeachers[$instructorId][$days[$dayIndex]][$timeSlots[$slotIndex][0]] = true;

                // 4. Auto Assign Siswa (Khusus Mapel Wajib)
                if (in_array($mapelName, $subjectsWajib)) {
                    foreach ($allStudentObjects as $stu) {
                        $gradeOnly = explode('-', $stu->class_grade)[0];
                        if ($gradeOnly === $jenjang) {
                            $stu->schedules()->syncWithoutDetaching([$sched->id]);
                        }
                    }
                }

                $slotIndex++;
                
                // Jika slot hari ini habis (misal sudah 4 mapel), pindah hari besok
                if ($slotIndex >= count($timeSlots)) {
                    $slotIndex = 0;     
                    $dayIndex++;        
                }

                // Jika hari sudah lewat Jumat, stop (mapel sisa tidak dibuatkan jadwal)
                if ($dayIndex >= count($days)) {
                    break; 
                }
            }
        }

        // BUAT ADMIN
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@school.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
    }
}