<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CreateAbilityData;
use App\Dto\UpdateAbilityData;
use App\Exceptions\OperationFailedException;
use App\Models\Ability;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class AbilityRepository
{
    public function all(): Collection
    {
        return Ability::query()->get();
    }

    public function find(int $id): ?Ability
    {
        return Ability::find($id);
    }

    public function create(CreateAbilityData $data): Ability
    {
        $image = $this->storeImage($data->image);
        $ability = new Ability();
        $ability->name_en = $data->name_en;
        $ability->name_jp = $data->name_jp;
        $ability->image = $image;
        $ability->save();

        return $ability;
    }

    public function update(Ability $ability, UpdateAbilityData $data): Ability
    {
        if ($data->name_en !== null) {
            $ability->name_en = $data->name_en;
        }
        if ($data->name_jp !== null) {
            $ability->name_jp = $data->name_jp;
        }
        if ($data->image !== null) {
            $image = $this->storeImage($data->image);
            $ability->image = $image;
        }
        $ability->save();

        return $ability;
    }

    public function delete(int $id): void
    {
        Ability::destroy($id);
    }

    protected function storeImage(UploadedFile $file): string
    {
        $path = $file->store(Ability::IMAGE_PATH, Ability::IMAGE_DISK);

        if (! $path) {
            throw new OperationFailedException('Unable save image');
        }

        return $path;
    }
}
