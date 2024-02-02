<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testSuccessfulIndex(): void
    {
        Location::factory()->count(3)->create();

        $response = $this->get(route('locations.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function testSuccessfulShow(): void
    {
        $location = Location::factory()->create();
        $location->refresh();

        $response = $this->get(route('locations.show', $location));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $location->id)
                ->where('data.name', $location->name)
                ->where('data.region', $location->region->value)
                ->where('data.children', [])
                ->missing('data.created_at')
                ->missing('data.updated_at')
            );
    }

    public function testSuccessfulStore(): void
    {
        $data = Location::factory()->make();

        $response = $this->postJson(route('locations.store'), $data->toArray());

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.id')
                ->where('data.name', $data->name)
                ->where('data.region', $data->region->value)
                ->etc());

    }

    public function testSuccessfulUpdate(): void
    {
        $location = Location::factory()->create();
        $data = Location::factory()->make();

        $response = $this->putJson(route('locations.update', $location), $data->toArray());

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $location->id)
                ->where('data.name', $data->name)
                ->where('data.region', $data->region->value)
                ->where('data.children', [])
            );
    }

    public function testSuccessfulDelete(): void
    {
        $location = Location::factory()->create();

        $response = $this->deleteJson(route('locations.destroy', $location));

        $response->assertStatus(204);
    }
}
