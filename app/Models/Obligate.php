<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obligate extends Model
{
    use HasFactory;
    protected $table='obligates';
    protected $fillable = [
        'content',
        'mark_id',

    ];
    protected $primaryKey='id';

    public function mark(){
        return $this->belongsTo(Mark::class,'mark_id','id');
    }
}
