<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom_teacherSubject extends Model
{
    use HasFactory;
    protected $table = 'classroom_teacher_subject';
    protected $fillable = [
        'teacherSubject_id',
        'classroom_id',
    ];
    protected $primaryKey = 'id';
}
