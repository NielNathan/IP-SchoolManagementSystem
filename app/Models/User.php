<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name', 'email', 'password', 'role'];

    // Relasi ke Profil
    public function student() {
        return $this->hasOne(Student::class);
    }
    public function instructor() {
        return $this->hasOne(Instructor::class);
    }
}