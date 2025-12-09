<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'subject_id',
        'instructor_id',
        'grade_level',
        'day',
        'time_start',
        'time_end',
        'quota',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    public function instructor() {
        return $this->belongsTo(Instructor::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'enrollments', 'schedule_id', 'student_id');
    }
}