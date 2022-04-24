<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weekly_schedule extends Model
{
    use HasFactory;
    protected $table='weekly_schedules';
    protected $fillable = [
        'classroom_id'
    ];
    protected $primaryKey='id';

    public function classroom(){
        return $this->belongsTo('App\Models\Classroom','classroom_id');
    }

    public function week_day(){
        return $this->hasMany('App\Models\Week_day','weekly_schedule_id','id');
    }
}
