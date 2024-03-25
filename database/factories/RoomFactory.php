<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'rate' => rand(60, 250),
            'min_capacity' => 1,
            'max_capacity' => rand(1, 6),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph(),
            'type_id' => Type::factory(),
        ];
    }
}
