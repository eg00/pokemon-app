<?php

namespace App\Http\Controllers;

use App\Dto\CreatePokemonData;
use App\Dto\UpdatePokemonData;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationFailedException;
use App\Http\Requests\Pokemon\CreatePokemonRequest;
use App\Http\Requests\Pokemon\UpdatePokemonRequest;
use App\Http\Resources\PokemonResource;
use App\Services\PokemonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class PokemonController extends Controller
{
    public function __construct(protected PokemonService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection|JsonResponse
    {
        try {
            return PokemonResource::collection($this->service->getAll());
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
    public function store(CreatePokemonRequest $request): PokemonResource|JsonResponse
    {
        try {
            $data = new CreatePokemonData(
                name: $request->input('name'),
                image: $request->image,
                shape: $request->input('shape'),
                locationId: $request->input('location_id'),
                abilityIds: $request->input('ability_ids'),
            );

            return (new PokemonResource($this->service->create($data)))
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
    public function show(int $id): PokemonResource|JsonResponse
    {
        try {
            return new PokemonResource($this->service->find($id));
        } catch (NotFoundException) {
            return new JsonResponse(
                'Not found',
                Response::HTTP_NOT_FOUND,
            );
        } catch (OperationFailedException) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePokemonRequest $request, int $id): PokemonResource|JsonResponse
    {

        try {
            $data = new UpdatePokemonData(
                name: $request->input('name'),
                image: $request->image,
                shape: $request->input('shape'),
                locationId: $request->input('location_id'),
                abilityIds: $request->input('ability_ids', []),
            );

            return new PokemonResource($this->service->update($id, $data));
        } catch (NotFoundException) {
            return new JsonResponse(
                'Not found',
                Response::HTTP_NOT_FOUND,
            );
        } catch (OperationFailedException) {
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
        } catch (OperationFailedException) {
            return new JsonResponse(
                'An error occurs while performing the operation',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new JsonResponse('Location deleted', Response::HTTP_NO_CONTENT);
    }
}
