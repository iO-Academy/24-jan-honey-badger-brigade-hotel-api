<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Type extends Model
{
    use HasFactory;

    public $hidden = ['created_at', 'updated_at'];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function booking(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Room::class);
    }
}
