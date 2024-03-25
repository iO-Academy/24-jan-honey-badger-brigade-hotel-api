<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

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
                'name'=> $this->faker->name(),
                'min_capacity'=>1,
                'max_capacity'=>rand(1,6),
                'image'=> $this->faker->imageUrl(),
                'type_id'=>Type::factory(),
        ];
    }
}
