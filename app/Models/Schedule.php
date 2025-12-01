<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    // Relasi-relasi penting
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    public function instructor() {
        return $this->belongsTo(Instructor::class);
    }
    // Melihat siapa saja siswa yang mengambil jadwal ini
    public function students() {
        return $this->belongsToMany(Student::class, 'enrollments', 'schedule_id', 'student_id');
    }
}