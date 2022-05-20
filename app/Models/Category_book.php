<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_book extends Model
{
    use HasFactory;
    protected $table='category_book';
    protected $fillable = [
        'book_id',
        'category_id'
    ];
    protected $primaryKey='id';
}
