<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table='books';
    protected $fillable = [
        'name',
        'file'
    ];
    protected $primaryKey='id';
    protected $hidden =[
        'pivot'
    ];

    public function categories(){
        return $this->belongsToMany('App\Models\Category','category_book','book_id','category_id','id','id');
    }

}
