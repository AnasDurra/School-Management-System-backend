<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper_file extends Model
{
    use HasFactory;
    protected $table='helper_files';
    protected $fillable = [
        'tutorial_id',
        'file',
    ];
    protected $primaryKey='id';

    public function tutorial(){
        return $this->belongsTo('app\Models\Tutorial','tutorial_id','id');
    }

}
