<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
        Room::factory()->create([
            'min_capacity' => 1,
        ]);
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-08-24',
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
            'end' => '2024-08-24',
        ]);
    }

    public function test_bookings_dateOverlapStart()
    {
        Room::factory()->create([
            'min_capacity' => 1,
        ]);
        Booking::factory()->create([
            'room_id' => 1,
            'start' => '2024-08-14',
            'end' => '2024-08-24',
        ]);
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-10',
            'end' => '2024-08-15',
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });

    }

    public function test_bookings_dateOverlapEnd()
    {
        Room::factory()->create([
            'min_capacity' => 1,
        ]);
        Booking::factory()->create([
            'room_id' => 1,
            'start' => '2024-08-14',
            'end' => '2024-08-24',
        ]);
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-08-26',
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });

    }

    public function test_bookings_dateOverlapBoth()
    {
        Room::factory()->create([
            'min_capacity' => 1,
        ]);
        Booking::factory()->create([
            'room_id' => 1,
            'start' => '2024-08-14',
            'end' => '2024-08-24',
        ]);
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-10',
            'end' => '2024-08-28',
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });

    }

    public function test_bookings_illogicalDates()
    {
        Room::factory()->create([
            'min_capacity' => 1,
            'max_capacity' => 2,
        ]);
        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-20',
            'end' => '2024-07-24',
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
    }

    public function test_bookings_alreadyBooked()
    {
        Room::factory()->create();
        Booking::factory()->create([
            'room_id' => 1,
            'customer' => 'Mrs Existing Booking',
            'guests' => 1,
            'start' => '2024-08-14',
            'end' => '2024-08-24',
        ]);

        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 1,
            'start' => '2024-08-21',
            'end' => '2024-08-25',
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
    }

    public function test_bookings_tooManyGuests()
    {
        Room::factory()->create([
            'min_capacity' => 1,
            'max_capacity' => 2,
        ]);

        $response = $this->postJson('/api/bookings', [
            'room_id' => 1,
            'customer' => 'Mrs Test',
            'guests' => 3,
            'start' => '2024-08-21',
            'end' => '2024-08-25',
        ]);
        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });
    }
    public function test_bookings_seeAllFuture()
    {
        Booking::factory()->create(['end' => '2024-12-24']);
        Booking::factory()->create(['end' => '2024-08-10']);
        Booking::factory()->create(['end' => '2023-12-24']);
        $response = $this->getJson('/api/bookings');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', 2, function (AssertableJson $json) {
                        $json->hasAll(['id', 'customer', 'start', 'end', 'created_at', 'room'])
                            ->whereAllType([
                                'id' => 'integer',
                                'customer' => 'string',
                                'start' => 'string',
                                'end' => 'string',
                                'created_at' => 'string',
                            ])
                            ->has('room', function (AssertableJson $json) {
                                $json->hasAll(['id', 'name'])
                                    ->whereAllType([
                                        'id' => 'integer',
                                        'name' => 'string'
                                    ]);
                            });
                    });
            });
    }
    public function test_bookings_noFinishedBookings()
    {
        Booking::factory()->create(['end' => '2024-12-24']);
        Booking::factory()->create(['end' => '2023-12-24']);
        $response = $this->getJson('/api/bookings');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->hasAll(['id', 'customer', 'start', 'end', 'created_at', 'room'])
                            ->whereAllType([
                                'id' => 'integer',
                                'customer' => 'string',
                                'start' => 'string',
                                'end' => 'string',
                                'created_at' => 'string',
                            ])
                            ->has('room', function (AssertableJson $json) {
                                $json->hasAll(['id', 'name'])
                                    ->whereAllType([
                                        'id' => 'integer',
                                        'name' => 'string'
                                    ]);
                            });
                    });
            });
    }
}

