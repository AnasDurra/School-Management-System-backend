<?php

namespace App\Models;

use App\Models\Paarent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table='students';
    protected $fillable = [
        'class_id',
        'classroom_id',
        'parent_id',
        'user_id'
    ];
//protected $primaryKey=null;

    public function  user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function parent(){
        return $this->belongsTo('App\Models\Paarent',"parent_id","user_id");
    }

    public function marks(){
        return $this->hasMany('App\Models\Mark','student_id','user_id');
    }
    public function classroom(){
        return $this->belongsTo('App\Models\Classroom','classroom_id','user_id');
    }

    public function absents(){
        return $this->hasMany('App\Models\Absent','student_id','user_id');
    }

}
