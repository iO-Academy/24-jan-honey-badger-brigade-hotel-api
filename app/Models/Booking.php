<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    public static function isAvailable(string $string, mixed $room_id)
    {
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }


}
