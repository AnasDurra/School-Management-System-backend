<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paarent extends Model
{
    use HasFactory;
    protected $table='paarents';
    protected $fillable = ['user_id'
    ];



    public function students(){
        return $this->hasMany('App\Models\Student','parent_id','user_id');
    }
}
