<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subfield extends Model
{
    use HasFactory;
    protected $table='subfields';
    protected $fillable = [
        'name',
        'subject_id'
    ];
    protected $primaryKey='id';


    public function teachers(){
        return $this->belongsToMany('App\Models\Teacher','teacher_subfiled','subfield_id','teacher_id','id','id');
    }
    public function subject(){
        return $this->belongsTo('App\Models\Subject','subject_id','id');
    }

}
