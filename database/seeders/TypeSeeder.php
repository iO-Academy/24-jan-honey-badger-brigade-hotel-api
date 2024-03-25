<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toInsert = [
            [
                'name' => 'Sea View'
            ],
            [
                'name' => 'High Roller Suite'
            ],
            [
                'name' => 'Family Room'
            ],
            [
                'name' => 'Basement'
            ],
            [
                'name' => 'Premier Room'
            ],
            [
                'name' => 'Basic Room'
            ],
            [
                'name' => 'Accessible Room'
            ],
            [
                'name' => 'Pet-Friendly Room'
            ],
            [
                'name' => 'Space Pirate Themed Room'
            ],
        ];

        foreach ($toInsert as $type) {
            DB::table('types')->insert($type);
        }
    }
}
