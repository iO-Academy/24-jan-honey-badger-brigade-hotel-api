<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     */
    public function test_bookings_createInvalid(): void
    {
        $response = $this->postJson('/api/bookings', []);
        $response->assertInvalid(['room_id', 'customer', 'guests', 'start', 'end']);
    }
    public function test_bookings_createSuccess(): void
    {
        Room::factory()->create();
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-08-24'
        ]);
        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
        $this->assertDatabaseHas('bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-08-24'
        ]);
    }
    public function test_bookings_badDates()
    {
        Room::factory()->create();
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-07-24'
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
    }
    public function test_bookings_alreadyBooked()
    {
        $room = Room::factory()->(Booking::factory())->create();

        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-08-14'
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
    }



}
