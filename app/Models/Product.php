<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'old_price',
        'image',
        'color',
        'rating',
        'user_id',
    ];

    
}
