<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category_id',
        'image',
        'description',
        'pages',
        'stock',
        'price',
    ];

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
