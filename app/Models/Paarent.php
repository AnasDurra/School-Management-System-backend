<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paarent extends Model
{
    use HasFactory;
    protected $table='paarents';
    protected $fillable = ['user_id','imported'
    ];


    public function  user(){
        return $this->belongsTo(User::class);
    }
    public function students(){
        return $this->hasMany('App\Models\Student','parent_id','user_id');
    }


    public function complaints(){
        return $this->hasMany('App\Models\Complaint','parent_id','user_id');
    }
}
