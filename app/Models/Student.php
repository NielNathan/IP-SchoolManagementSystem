<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'nis',
        'fullname',
        'class_grade',
        'phone',
    ];

    // Relasi balik ke User
    public function user() {
        return $this->belongsTo(User::class);
    }
    // Relasi melihat jadwal yang diambil
    public function schedules() {
        return $this->belongsToMany(Schedule::class, 'enrollments', 'student_id', 'schedule_id');
    }
}