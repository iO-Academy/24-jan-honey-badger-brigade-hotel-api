<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    public $hidden = ['created_at', 'updated_at'];
    public function types(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
