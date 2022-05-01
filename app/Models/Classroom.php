<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table='classrooms';
    protected $fillable = [
        'name',
        'capacity',
        'class_id'
    ];
    protected $primaryKey='id';

    public function students(){
        return $this->hasMany('App\Models\Student','classroom_id','id');
    }

    public function class(){
        return $this->belongsTo('App\Models\Classes',"class_id",'id');
    }

    public function weekly_schedule(){
        return $this->hasOne('App\Models\Weekly_schedule','classroom_id');
    }
}
