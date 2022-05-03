<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_classroom extends Model
{
    use HasFactory;
    protected $table='teacher_classroom';
    protected $fillable = [
        'classroom_id',
        'teacher_id'
    ];
    protected $primaryKey='id';
}
