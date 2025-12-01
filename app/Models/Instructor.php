<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $guarded = [];

    // Relasi ke User Login (Akun Pak Guru)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Guru mengajar banyak jadwal
    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}