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
            'name' => $this->faker->sentence(1),
            'min_capacity' => rand(1, 3),
            'max_capacity' => rand(3, 6),
            'image' => 'https://picsum.photos/400/400',
            'type_id' => Type::factory(),
            'rate'=> rand(1,5),
            'description' => $this->faker->sentence(5)
        ];
    }
}
