<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week_day_subject extends Model
{
    use HasFactory;
    protected $table='week_day_subjects';
    protected $fillable = [
        'subject_id',
        'week_day_id',
        'order'
    ];
    protected $primaryKey='id';

    public function week_day(){
        return $this->belongsTo('App\Models\Week_day','week_day_id','id');
    }



}
