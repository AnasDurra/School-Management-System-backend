<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teacher_subject extends Model
{
    use HasFactory;
    protected $table='teacher_subject';
    protected $fillable = [
        'subject_id',
        'teacher_id'
    ];
    protected $primaryKey='id';

    public function classrooms()
    {
        return $this->belongsToMany('App\Models\Classroom', "classroom_teacher_subject", 'teacherSubject_id', 'classroom_id', 'id', 'id');
    }
}
