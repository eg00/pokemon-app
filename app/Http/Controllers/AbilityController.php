<?php

namespace App\Http\Controllers;

use App\Dto\CreateAbilityData;
use App\Dto\UpdateAbilityData;
use App\Exceptions\NotFoundException;
use App\Exceptions\OperationFailedException;
use App\Http\Requests\Ability\CreateAbilityRequest;
use App\Http\Requests\Ability\UpdateAbilityRequest;
use App\Http\Resources\AbilityResource;
use App\Services\AbilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class AbilityController extends Controller
{
    public function __construct(protected AbilityService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection|JsonResponse
    {
        try {
            return AbilityResource::collection($this->service->getAll());
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
    public function store(CreateAbilityRequest $request): AbilityResource|JsonResponse
    {
        try {
            $data = new CreateAbilityData(
                nameEn: $request->input('name_en'),
                nameJp: $request->input('name_jp'),
                image: $request->image,
            );

            return (new AbilityResource($this->service->create($data)))
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
    public function show(int $id): AbilityResource|JsonResponse
    {
        try {
            return new AbilityResource($this->service->find($id));
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
    public function update(UpdateAbilityRequest $request, int $id): AbilityResource|JsonResponse
    {

        try {
            $data = new UpdateAbilityData(
                nameEn: $request->input('name_en'),
                nameJp: $request->input('name_jp'),
                image: $request->image,
            );

            return new AbilityResource($this->service->update($id, $data));
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
