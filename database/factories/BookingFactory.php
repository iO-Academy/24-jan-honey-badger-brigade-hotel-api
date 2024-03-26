<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $start = $this->faker->dateTimeBetween('+1 day', '+1 year');
        $length = rand(1, 21) . ' days';
        $end = date_add($start, date_interval_create_from_date_string($length));

        return [
            'room_id' => Room::factory(),
            'customer' => $this->faker->name(),
            'guests' => rand(1, 6),
            'start' => $start,
            'end' => $end
            ];
    }
}
