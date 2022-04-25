<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teacher_subfiled extends Model
{
    use HasFactory;
    protected $table='teacher_subfiled';
    protected $fillable = [
        'teacher_id',
        'subfield_id'
    ];
    protected $primaryKey='id';
}
