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
        return $this->hasMany('App\Models\Teacher',"subject_id",'id');
    }

    public function classes(){
        return $this->belongsToMany('App\Models\Classes','class_subject','subject_id','class_id','id','id');
    }

    public function subfields(){
        return $this->hasMany('App\Models\Subfield',"subject_id",'id');
    }

}
