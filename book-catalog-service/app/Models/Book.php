<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author', 
        'isbn',
        'publication_year',
        'stock',
        'available',
        'category_id',
        'description',
        'cover'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}