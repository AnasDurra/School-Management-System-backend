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
    public function subject(){
        return $this->belongsTo('App\Models\Subject',"subject_id",'id');
    }

    public function subfields(){
        return $this->belongsToMany('App\Models\Subfield','teacher_subfield','teacher_id','subfield_id','user_id','id');
    }
}
