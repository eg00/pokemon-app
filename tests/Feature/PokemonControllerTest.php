<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ability;
use App\Models\Location;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PokemonControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testSuccessfulIndex(): void
    {
        Pokemon::factory()->count(3)->create();

        $response = $this->get(route('pokemons.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function testSuccessfulShow(): void
    {
        $pokemon = Pokemon::factory()->create();
        $pokemon->refresh();

        $response = $this->get(route('pokemons.show', $pokemon));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $pokemon->id)
                ->where('data.name', $pokemon->name)
                ->where('data.shape', $pokemon->shape)
                ->missing('data.created_at')
                ->missing('data.updated_at')
                ->etc()
            );
    }

    public function testSuccessfulStore(): void
    {
        $data = Pokemon::factory()->make();

        $file = UploadedFile::fake()->image('pokemon.jpg');

        $abilities = Ability::factory()->count(3)->create();

        $response = $this->postJson(
            route('pokemons.store'),
            array_merge($data->toArray(), ['image' => $file, 'ability_ids' => $abilities->pluck('id')->toArray()]),
        );

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.id')
                ->where('data.name', $data->name)
                ->where('data.shape', $data->shape->value)
                ->etc());

    }

    public function testSuccessfulUpdate(): void
    {
        $location = Pokemon::factory()->create();
        $data = Pokemon::factory()->make();

        $file = UploadedFile::fake()->image('pokemon.jpg');

        $response = $this->putJson(
            route('pokemons.update', $location),
            ['name' => $data->name, 'shape' => $data->shape->value, 'image' => $file],
        );

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.id', $location->id)
                ->where('data.name', $data->name)
                ->where('data.shape', $data->shape->value)
                ->has('data.image')
                ->etc(),
            );
    }

    public function testSuccessfulDelete(): void
    {
        $location = Location::factory()->create();

        $response = $this->deleteJson(route('locations.destroy', $location));

        $response->assertStatus(204);
    }
}
