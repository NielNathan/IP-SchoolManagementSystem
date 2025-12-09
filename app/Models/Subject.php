<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = []; 

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

    public function materials() {
        return $this->hasMany(Material::class);
    }
}