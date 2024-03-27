<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            Room::factory()->has(Booking::factory()->count(4))->create([
                'type_id' => rand(1, 9),
            ]);
        }
    }
}
