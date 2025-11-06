<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    protected $fillable = [
        'role',
        'user_id'
    ];

    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
