<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'author',
    ];


    // public function author(): HasOne
    // {
    //     return $this->hasOne(User::class, 'author');
    // }
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
