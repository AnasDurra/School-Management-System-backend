<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table='students';
    protected $fillable = [
        'name'
    ];

    //maybe not important
    public function marks(){
        return $this->hasMany('App\Models\Mark','type_id','id');
    }
}
