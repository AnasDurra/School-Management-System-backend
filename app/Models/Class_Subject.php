<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_Subject extends Model
{
    use HasFactory;
    protected $table='class_subject';
    protected $fillable = [
        'subject_id',
        'class_id'
    ];
    protected $primaryKey='id';

}
