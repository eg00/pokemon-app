<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CreateAbilityData;
use App\Dto\UpdateAbilityData;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationFailedException;
use App\Models\Ability;
use App\Repositories\AbilityRepository;
use Exception;
use Illuminate\Support\Collection;

class AbilityService
{
    public function __construct(protected AbilityRepository $repository)
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
    public function find(int $id): Ability
    {
        try {
            $ability = $this->repository->find($id);
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }

        if (! $ability) {
            throw new NotFoundException();
        }

        return $ability;
    }

    public function create(CreateAbilityData $data): Ability
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
    public function update(int $id, UpdateAbilityData $data): Ability
    {
        $ability = $this->find($id);
        try {
            return $this->repository->update($ability, $data);
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
