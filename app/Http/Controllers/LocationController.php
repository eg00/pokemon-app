<?php

namespace App\Http\Controllers;

use App\Dto\CreateLocationData;
use App\Dto\UpdateLocationData;
use App\Enums\Region;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationFailedException;
use App\Http\Requests\Location\CreateLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    public function __construct(protected LocationService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection|JsonResponse
    {
        try {
            return LocationResource::collection($this->service->getAll());
        } catch (OperationFailedException $e) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLocationRequest $request): LocationResource|JsonResponse
    {
        try {
            $data = new CreateLocationData(
                name: $request->input('name'),
                region: $request->input('region'),
                parentId: $request->input('parent_id'),
            );

            return (new LocationResource($this->service->create($data)))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (OperationFailedException $e) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): LocationResource|JsonResponse
    {
        try {
            return new LocationResource($this->service->find($id));
        } catch (NotFoundException $e) {
            return new JsonResponse(
                'Not found',
                Response::HTTP_NOT_FOUND,
            );
        } catch (OperationFailedException $e) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocationRequest $request, int $id): LocationResource|JsonResponse
    {
        try {
            $data = new UpdateLocationData(
                name: $request->input('name'),
                region: $request->input('region'),
                parentId: $request->input('parent_id'),
            );

            return new LocationResource($this->service->update($id, $data));
        } catch (NotFoundException $e) {
            return new JsonResponse(
                'Not found',
                Response::HTTP_NOT_FOUND,
            );
        } catch (OperationFailedException $e) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
        } catch (OperationFailedException $e) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new JsonResponse('Location deleted', Response::HTTP_NO_CONTENT);
    }
}
