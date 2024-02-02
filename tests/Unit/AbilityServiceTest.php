<?php

namespace Tests\Unit;

use App\Dto\CreateAbilityData;
use App\Dto\UpdateAbilityData;
use App\Models\Ability;
use App\Services\AbilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AbilityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AbilityService $service;

    protected function setUp(): void
    {
        $this->service = app(AbilityService::class);
        parent::setUp();
    }

    /**
     * A basic test example.
     */
    public function testGetAll(): void
    {
        Ability::factory()->count(3)->create();

        $this->assertCount(3, $this->service->getAll());

        $this->assertEquals(Ability::all(), $this->service->getAll());
    }

    public function testFind(): void
    {
        $ability = Ability::factory()->create();
        $ability->refresh();

        $this->assertEquals($ability->name_jp, $this->service->find($ability->id)->name_jp);
    }

    public function testCreate(): void
    {
        Storage::fake('public');
        $data = Ability::factory()->make();

        $dto = new CreateAbilityData(
            name_en: $data->name_en,
            name_jp: $data->name_en,
            image: UploadedFile::fake()->image('ability.jpg'),
        );
        $this->service->create($dto);

        $this->assertDatabaseHas('abilities', ['name_en' => $data->name_en, 'name_jp' => $data->name_en]);
        Storage::disk('public')->assertExists(Ability::query()->firstOrFail()->image);
    }

    public function testUpdate(): void
    {
        $ability = Ability::factory()->create();
        $data = Ability::factory()->make();
        $this->service->update($ability->id, new UpdateAbilityData(
            name_en: $data->name_en,
            name_jp: $data->name_en,
            image: UploadedFile::fake()->image('ability.jpg'),
        ));

        $this->assertDatabaseHas('abilities', ['name_en' => $data->name_en, 'name_jp' => $data->name_en]);
        Storage::disk('public')->assertExists(Ability::query()->firstOrFail()->image);
    }

    public function testDelete(): void
    {
        $ability = Ability::factory()->create();
        $this->service->delete($ability->id);
        $this->assertDatabaseMissing('abilities', $ability->toArray());
    }
}
