<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 year', '+1 year');
        $stay = rand(1,21);
        $open = Carbon::parse($start);
        $end = $open->addDays($stay);

        return [
            'room_id' => Room::factory(),
            'customer' => $this->faker->name(),
            'guests' => rand(1, 6),
            'start' => $start,
            'end' => $end,
        ];
    }
}
