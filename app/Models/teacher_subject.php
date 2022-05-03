<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teacher_subject extends Model
{
    use HasFactory;
    protected $table='teacher_subject';
    protected $fillable = [
        'subject_id',
        'teacher_id'
    ];
    protected $primaryKey='id';
}
