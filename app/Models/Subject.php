<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table='subjects';
    protected $fillable = [
        'name'
    ];
    protected $primaryKey='id';

    protected $hidden =[
        'pivot'
    ];

    public function teachers(){
        return $this->belongsToMany('App\Models\Teacher',"teacher_subject",'subject_id','teacher_id','id','user_id');
    }

    public function classes(){
        return $this->belongsToMany('App\Models\Classes','class_subject','subject_id','class_id','id','id');
    }

    public function tutorials(){
        return $this->hasMany('App\Models\Tutorial','subject_id','id');
    }
    public function  marks(){
        return $this->hasMany(Mark::class,'subject_id','id');
    }



}
