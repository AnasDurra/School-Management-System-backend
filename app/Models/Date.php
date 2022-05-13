<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table='dates';
    protected $fillable = [
        'date'
    ];
    protected $primaryKey='id';

    public function events(){
       return $this->hasMany(Event::class,'date_id','id');
    }
}
