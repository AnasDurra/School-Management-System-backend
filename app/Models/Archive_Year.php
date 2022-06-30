<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive_Year extends Model
{
    use HasFactory;
    protected $table='archive_years';
    protected $fillable = [
       'year',
        'active'
    ];
    protected $primaryKey='id';
}