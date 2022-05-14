<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $table='assignments';
    protected $fillable = [
        'content',
        'date',
        'teacher_id',
        'classroom_id',
    ];
    protected $primaryKey='id';

    public function teacher(){
        return $this->belongsTo('App\Models\Teacher','teacher_id','user_id');
    }

    public function classroom(){
        return $this->belongsTo('App\Models\Classroom','classroom_id','id');
    }


}
