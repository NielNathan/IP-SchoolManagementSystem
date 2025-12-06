<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $guarded = [];

    // Relasi ke Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
