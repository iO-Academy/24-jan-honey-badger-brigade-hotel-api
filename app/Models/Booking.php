<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;
    public $hidden = ['updated_at', 'created_at'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
