<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table='events';
    protected $fillable = [
        'date_id',
        'content'
    ];
    protected $primaryKey='id';

    public function date(){
      return  $this->belongsTo(Date::class,'date_id','id');
    }
}
