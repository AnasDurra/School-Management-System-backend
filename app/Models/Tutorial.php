<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;
    protected $table='tutorials';
    protected $fillable = [
        'name',
        'description',
        'class_id',
        'subject_id',
        'teacher_id',
        'views',
        'file'
    ];
    protected $primaryKey='id';

    public function helper_files(){
        return $this->hasMany('App\Models\Helper_file','tutorial_id','id');
    }

    public function class(){
        return $this->belongsTo('App\Models\Classes',"class_id",'id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject','subject_id','id');
    }
    public function teacher(){
     return $this->belongsTo(Teacher::class,'teacher_id','user_id');
    }
}
