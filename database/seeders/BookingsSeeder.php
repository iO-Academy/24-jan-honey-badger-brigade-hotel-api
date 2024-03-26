<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {

            Booking::factory()
                ->count(1)->create();
        }
    }
}
