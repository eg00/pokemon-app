<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CreateLocationData;
use App\Dto\UpdateLocationData;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationFailedException;
use App\Models\Location;
use App\Repositories\LocationRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class LocationService
{
    public function __construct(protected LocationRepository $repository)
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
    public function find(int $id): Location
    {
        try {
            $location = $this->repository->find($id);
        } catch (ModelNotFoundException) {
            throw new NotFoundException();
        } catch (Exception $e) {
            throw new OperationFailedException($e->getMessage(), 0, $e);
        }

        return $location;
    }

    public function create(CreateLocationData $data): Location
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
    public function update(int $id, UpdateLocationData $data): Location
    {
        $location = $this->find($id);
        try {
            return $this->repository->update($location, $data);
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
