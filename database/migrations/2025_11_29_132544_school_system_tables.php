<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Update tabel Users (bawaan) - Tambah kolom Role
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student'); // admin, student, teacher
        });

        // 2. Tabel Subjects (Mata Pelajaran)
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 3. Tabel Instructors (Guru)
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Link ke Login
            $table->string('nip')->unique();
            $table->string('fullname');
            $table->timestamps();
        });

        // 4. Tabel Students (Siswa)
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Link ke Login
            $table->string('nis')->unique();
            $table->string('fullname');
            $table->string('class_grade'); // X, XI, XII
            $table->timestamps();
        });

        // 5. Tabel Schedules (Jadwal Kelas)
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('instructor_id')->constrained('instructors');
            $table->string('grade_level');
            $table->string('day');
            $table->time('time_start');
            $table->time('time_end');
            $table->integer('quota')->default(30);
            $table->timestamps();
        });

        // 6. Tabel Pivot (KRS / Pengambilan SKS)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('schedule_id')->constrained('schedules');
            $table->timestamps();
            $table->unique(['student_id', 'schedule_id']); // Gaboleh ambil kelas sama 2x
        });
    }

    public function down()
    {
        // Drop urut dari bawah ke atas
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('students');
        Schema::dropIfExists('instructors');
        Schema::dropIfExists('subjects');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};