<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'is_returned',
    ];

    protected $casts = [
        'is_returned' => 'boolean',
    ];
} 