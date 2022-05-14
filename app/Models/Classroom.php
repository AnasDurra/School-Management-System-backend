<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classrooms';
    protected $fillable = [
        'name',
        'capacity',
        'class_id'
    ];
    protected $primaryKey = 'id';
    protected $hidden =[
        'pivot'
    ];
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'classroom_id', 'id');
    }

    public function teachers()
    {
        return $this->belongsToMany('App\Models\Teacher', "teacher_classroom", 'classroom_id', 'teacher_id', 'id', 'user_id');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\Classes', "class_id", 'id');
    }

    public function weekDay()
    {
        return $this->hasMany('App\Models\Week_day', 'classroom_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany('App\Models\Assignment', 'classroom_id', 'id');
    }
}
