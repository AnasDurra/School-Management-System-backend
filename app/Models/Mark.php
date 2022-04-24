<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;
    protected $table='marks';
    protected $fillable = [
        'value',
        'subfield_id',
        'subject_id',
        'student_id'
    ];
    protected $primaryKey='id';

    public function student(){
        return $this->belongsTo('app\Models\Student','student_id','id');
    }


}
