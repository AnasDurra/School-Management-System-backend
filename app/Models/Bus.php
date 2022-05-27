<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    protected $table='buses';
    protected $fillable = [
        'name',
        'capacity',
        'supervisor',
        'supervisor_num',
        'supervisor_address'
    ];
    protected $primaryKey='id';

    public function students(){
        return $this->hasMany('App\Models\Student','bus_id','id');
    }
}
