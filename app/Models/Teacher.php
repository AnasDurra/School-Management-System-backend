<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $table='teachers';
    protected $fillable = [
        'subject_id',
        'user_id',
    ];
   // protected $primaryKey='id';

    public function  user(){
        return $this->belongsTo(User::class);
    }
    public function subjects(){
        return $this->belongsToMany('App\Models\Subject',"teacher_subject",'teacher_id','subject_id','user_id','id');
    }


}
