<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table='classes';
    protected $fillable = [
        'name'
    ];
    protected $primaryKey='id';

    public function classrooms(){
        return $this->hasMany('App\Models\Classroom',"class_id",'id');
    }


    public function subjects(){
        return $this->belongsToMany('App\Models\Subject','class_subject','class_id','subject_id','id','id');
    }
    public  function  subfields(){
        return $this->subjects()->with('subfields');
    }
}
