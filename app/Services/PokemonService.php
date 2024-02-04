<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CreatePokemonData;
use App\Dto\UpdatePokemonData;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationFailedException;
use App\Models\Pokemon;
use App\Repositories\PokemonRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PokemonService
{
    public function __construct(protected PokemonRepository $repository)
    {
    }

    /**
     * @throws OperationFailedException
     */
    public function getAll(): Collection
    {
        try {
            return $this->repository->all();
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @throws NotFoundException
     * @throws OperationFailedException
     */
    public function find(int $id): Pokemon
    {
        try {
            $pokemon = $this->repository->find($id);
        } catch (ModelNotFoundException) {
            throw new NotFoundException();
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }

        return $pokemon;
    }

    public function create(CreatePokemonData $data): Pokemon
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @throws NotFoundException
     * @throws OperationFailedException
     */
    public function update(int $id, UpdatePokemonData $data): Pokemon
    {
        $pokemon = $this->find($id);
        try {
            return $this->repository->update($pokemon, $data);
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @throws OperationFailedException
     */
    public function delete(int $id): void
    {
        try {
            $this->repository->delete($id);
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }
    }
}
