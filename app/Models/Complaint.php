<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $table='complaints';
    protected $fillable = [
        'title',
        'content',
        'parent_id',
        'admin_id',
        'date',
        'seen'
    ];
    protected $primaryKey='id';
    public function parent(){
        return $this->belongsTo('App\Models\Paarent',"parent_id","user_id");
    }

    public function admin(){
        return $this->belongsTo('App\Models\Admin',"admin_id","user_id");
    }

}
