<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public static function isAvailable(string $string, mixed $room_id)
    {
    }

    public static function create(array $array)
    {
    }
}
