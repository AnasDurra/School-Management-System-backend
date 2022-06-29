<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_tag extends Model
{
    use HasFactory;
    protected $table='admin_tags';
    protected $fillable = [
        'admin_id',
        'tag_id',
    ];
    protected $primaryKey='id';
}
