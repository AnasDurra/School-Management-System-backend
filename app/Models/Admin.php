<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table ='admins';
    protected $fillable=[
     'user_id',
];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function complaints(){
        return $this->hasMany('App\Models\Complaint','admin_id','user_id');
    }



}
