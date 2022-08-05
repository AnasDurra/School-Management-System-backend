<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_responsibility extends Model
{
    use HasFactory;
    protected $table='admin_responsibilities';
    protected $fillable = [
        'admin_id',
        'responsibility_id',
    ];
    protected $primaryKey='id';
}
