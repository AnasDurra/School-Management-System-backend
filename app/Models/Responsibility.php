<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    use HasFactory;
    protected $table='responsibilities';
    protected $fillable = [
        'name'
    ];
    protected $primaryKey='id';

    protected $hidden =[
        'pivot'
    ];

    public function admins(){
        return $this->belongsToMany('App\Models\Admin','admin_responsibilities','responsibility_id','admin_id','id','user_id');
    }
}
