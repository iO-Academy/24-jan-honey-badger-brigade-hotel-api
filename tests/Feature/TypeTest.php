<?php

namespace Tests\Feature;

use App\Models\Type;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TypeTest extends TestCase
{
    public function test_getAllTypes(): void
    {
        Type::factory()->create();

        $response = $this->getJson('/api/types');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->whereType('message', 'string')
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->hasAll(['id', 'name'])
                            ->whereAllType([
                                'id'=>'integer',
                                'name'=>'string'
                            ]);
                    });
            });

    }
}
