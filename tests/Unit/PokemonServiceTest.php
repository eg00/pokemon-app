<?php

namespace Tests\Unit;

use App\Dto\CreatePokemonData;
use App\Dto\UpdatePokemonData;
use App\Models\Pokemon;
use App\Services\PokemonService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PokemonServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PokemonService $service;

    protected function setUp(): void
    {
        $this->service = app(PokemonService::class);
        parent::setUp();
    }

    /**
     * A basic test example.
     */
    public function testGetAll(): void
    {
        Pokemon::factory()->count(3)->create();

        $this->assertCount(3, $this->service->getAll());

        $this->assertEquals(
            Pokemon::query()->with(['location', 'abilities'])->get(),
            $this->service->getAll(),
        );
    }

    public function testFind(): void
    {
        $pokemon = Pokemon::factory()->create();
        $pokemon->refresh();

        $this->assertEquals($pokemon->name, $this->service->find($pokemon->id)->name);
    }

    public function testCreate(): void
    {
        Storage::fake('public');
        $data = Pokemon::factory()->make();

        $dto = new CreatePokemonData(
            name: $data->name,
            image: UploadedFile::fake()->image('pokemon.jpg'),
            shape: $data->shape->value,
            locationId: $data->location_id,
            abilityIds: [],
        );
        $this->service->create($dto);

        $this->assertDatabaseHas('pokemon', ['name' => $data->name]);
        Storage::disk('public')->assertExists(Pokemon::query()->firstOrFail()->image);
    }

    public function testUpdate(): void
    {
        $ability = Pokemon::factory()->create();
        $data = Pokemon::factory()->make();
        $this->service->update($ability->id, new UpdatePokemonData(
            name: $data->name,
            shape: $data->shape->value,
            image: UploadedFile::fake()->image('pokemon.jpg'),
            locationId: null,
            abilityIds: [],
        ));

        $this->assertDatabaseHas('pokemon', ['name' => $data->name, 'shape' => $data->shape]);
        Storage::disk('public')->assertExists(Pokemon::query()->firstOrFail()->image);
    }

    public function testDelete(): void
    {
        $pokemon = Pokemon::factory()->create();
        $this->service->delete($pokemon->id);
        $this->assertDatabaseMissing('pokemon', $pokemon->toArray());
    }
}
