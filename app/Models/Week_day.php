<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week_day extends Model
{
    use HasFactory;
    protected $table='week_days';
    protected $fillable = [
        'day',
    ];
    protected $primaryKey='id';

    public function classroom(){
        return $this->belongsTo('App\Models\Classroom','classroom_id','id');
    }

    public function week_day_subject(){
        return $this->hasMany('App\Models\Week_day_subject','week_day_id','id');
    }

}
