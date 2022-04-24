<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table = "school_classes";
    protected $fillable = [
        'grade'
    ];

    public function students()
    {
        return $this->hasMany(student::class, 'class_id');
    }
}
