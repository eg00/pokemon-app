<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CreatePokemonData;
use App\Dto\UpdatePokemonData;
use App\Enums\Shape;
use App\Exceptions\OperationFailedException;
use App\Models\Pokemon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PokemonRepository
{
    public function __construct(
        protected LocationRepository $locationRepository,
        protected AbilityRepository $abilityRepository,
    ) {
    }

    public function all(): Collection
    {
        return Pokemon::query()->with(['location', 'abilities'])->get();
    }

    public function getFiltered(string $location): Collection
    {
        return Pokemon::query()
            ->with(['location', 'abilities'])
            ->filteredByLocation($location)
            ->get();
    }

    public function find(int $id): Pokemon
    {
        /** @var Pokemon */
        return Pokemon::query()->with(['location', 'abilities'])->findOrFail($id);
    }

    public function create(CreatePokemonData $data): Pokemon
    {
        $image = $this->storeImage($data->image);
        $abilities = $this->abilityRepository->findMany($data->abilityIds);

        $pokemon = new Pokemon();
        $pokemon->name = $data->name;
        $pokemon->shape = Shape::from($data->shape);
        $pokemon->image = $image;
        $pokemon->location_id = $data->locationId;
        $pokemon->save();
        $pokemon->abilities()->attach($abilities);

        return $pokemon;
    }

    public function update(Pokemon $pokemon, UpdatePokemonData $data): Pokemon
    {
        if ($data->name !== null) {
            $pokemon->name = $data->name;
        }
        if ($data->image !== null) {
            $image = $this->storeImage($data->image);
            $pokemon->image = $image;
        }

        if ($data->shape !== null) {
            $pokemon->shape = Shape::from($data->shape);
        }
        if ($data->locationId !== null) {
            $location = $this->locationRepository->find($data->locationId);
            $pokemon->location()->save($location);
        }
        if (! empty($data->abilityIds)) {
            $abilities = $this->abilityRepository->findMany($data->abilityIds);
            $pokemon->abilities()->sync($abilities);
        }
        $pokemon->save();

        return $pokemon;
    }

    public function delete(int $id): void
    {
        $this->removeImage($id);
        Pokemon::destroy($id);
    }

    protected function storeImage(UploadedFile $file): string
    {
        $path = $file->store(Pokemon::IMAGE_PATH, Pokemon::IMAGE_DISK);

        if (! $path) {
            throw new OperationFailedException('Unable save image');
        }

        return $path;
    }

    protected function removeImage(int $id): void
    {
        $pokemon = $this->find($id);

        Storage::disk(Pokemon::IMAGE_DISK)->delete($pokemon->image);
    }

    public function sortByLocation(Collection $pokemons, string $sortOrder): Collection
    {
        return $sortOrder === 'asc'
            ? $pokemons->sortBy(fn (Pokemon $pokemon) => $pokemon->location->name)
            : $pokemons->sortByDesc(fn (Pokemon $pokemon) => $pokemon->location->name);
    }
}
