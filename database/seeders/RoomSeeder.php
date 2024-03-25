<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++)
        {
            Room::factory()
                ->recycle(Type::factory()->create())
                ->count(3)->create();
        }
    }
}
