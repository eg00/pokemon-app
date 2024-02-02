<?php

namespace Tests\Unit;

use App\Dto\CreateLocationData;
use App\Dto\UpdateLocationData;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LocationService $service;

    protected function setUp(): void
    {
        $this->service = app(LocationService::class);
        parent::setUp();
    }

    /**
     * A basic test example.
     */
    public function testGetAll(): void
    {
        Location::factory()->count(3)->create();

        $this->assertCount(3, $this->service->getAll());

        $this->assertEquals(Location::all(), $this->service->getAll());
    }

    public function testFind(): void
    {
        $location = Location::factory()->create();
        $location->refresh();

        $this->assertEquals($location->name, $this->service->find($location->id)->name);
    }

    public function testCreate(): void
    {
        $data = Location::factory()->make();

        $dto = new CreateLocationData(
            name: $data->name,
            region: $data->region,
            parentId: $data->parent_id,
        );
        $this->service->create($dto);
        $this->assertDatabaseHas('locations', $data->toArray());
    }

    public function testUpdate(): void
    {
        $location = Location::factory()->create();
        $data = Location::factory()->make();
        $this->service->update($location->id, new UpdateLocationData(
            name: $data->name,
            region: $data->region,
            parentId: $data->parent_id,
        ));
        $this->assertDatabaseHas('locations', $data->toArray());
    }

    public function testDelete(): void
    {
        $location = Location::factory()->create();
        $this->service->delete($location->id);
        $this->assertDatabaseMissing('locations', $location->toArray());
    }
}
