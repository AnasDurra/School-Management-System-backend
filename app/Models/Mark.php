<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;
    protected $table='marks';
    protected $fillable = [
        'value',
        'subject_id',
        'student_id',
        'type_id'
    ];
    protected $primaryKey='id';

    public function student(){
        return $this->belongsTo('app\Models\Student','student_id','user_id');
    }

    public function type(){
        return $this->belongsTo('app\Models\Type','type_id','id');
    }
    public function obligate(){
        return $this->hasOne(Obligate::class,'mark_id','id');
    }






}
