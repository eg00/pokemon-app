<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CreateLocationData;
use App\Dto\UpdateLocationData;
use App\Models\Location;
use Illuminate\Support\Collection;

class LocationRepository
{
    public function all(): Collection
    {
        return Location::query()->get();
    }

    public function find(int $id): ?Location
    {
        return Location::find($id);
    }

    public function create(CreateLocationData $data): Location
    {
        $location = new Location();
        $location->name = $data->name;
        $location->region = $data->region;
        $location->parent_id = $data->parentId;
        $location->save();

        return $location;
    }

    public function update(Location $location, UpdateLocationData $data): Location
    {
        if ($data->name !== null) {
            $location->name = $data->name;
        }
        if ($data->region !== null) {
            $location->region = $data->region;
        }
        $location->parent_id = $data->parentId;
        $location->save();

        return $location;
    }

    public function delete(int $id): void
    {
        Location::destroy($id);
    }
}