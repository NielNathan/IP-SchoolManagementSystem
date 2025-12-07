<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'file_path', 'subject_id', 'instructor_id', 'grade_level'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}