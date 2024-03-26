<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getRooms(): void
    {
        Room::factory()->create();

        $response = $this->getJson('/api/rooms');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->hasAll(['id', 'name', 'image', 'min_capacity', 'max_capacity', 'type'])
                            ->whereAllType([
                                'id' => 'integer',
                                'name' => 'string',
                                'image' => 'string',
                                'min_capacity' => 'integer',
                                'max_capacity' => 'integer',
                            ])
                            ->has('type', function (AssertableJson $json) {
                                $json->hasAll(['id', 'name'])
                                    ->whereAllType([
                                        'id' => 'integer',
                                        'name' => 'string',
                                    ]);
                            });
                    });
            });
    }

    public function test_findRoom_success(): void
    {
        Room::factory()->create();

        $response = $this->getJson('/api/rooms/1');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', function (AssertableJson $json) {
                        $json->hasAll(['id', 'name', 'rate', 'image', 'min_capacity', 'max_capacity', 'description', 'type'])
                            ->whereAllType([
                                'id' => 'integer',
                                'name' => 'string',
                                'rate' => 'integer',
                                'image' => 'string',
                                'min_capacity' => 'integer',
                                'max_capacity' => 'integer',
                                'description' => 'string',
                            ])
                            ->has('type', function (AssertableJson $json) {
                                $json->hasAll(['id', 'name'])
                                    ->whereAllType([
                                        'id' => 'integer',
                                        'name' => 'string',
                                    ]);
                            });
                    });
            });
    }

    public function test_findRoom_notFound(): void
    {
        $response = $this->getJson('/api/rooms/100');

        $response->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->whereType('message', 'string');
            });
    }

    public function test_getRoomsByType(): void
    {
        Room::factory()->count(10)->create();
        $response = $this->getJson('/api/rooms/100');
    }
}
