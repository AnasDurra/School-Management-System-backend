<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absent extends Model
{
    use HasFactory;
    protected $table='absents';
    protected $fillable = [
        'student_id',
        'date',
        'is_justified'
    ];
    protected $primaryKey='id';

    public function student(){
        return $this->belongsTo('app\Models\Student','student_id','user_id');
    }

}
