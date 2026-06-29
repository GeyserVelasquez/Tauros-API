<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementKardex\StoreMovementKardexRequest;
use App\Http\Requests\MovementKardex\UpdateMovementKardexRequest;
use App\Http\Resources\MovementKardexResource;
use App\Models\MovementKardex;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class MovementKardexController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(MovementKardex::class, $request);

        $movements = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return MovementKardexResource::collection($movements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovementKardexRequest $request)
    {
        $data = $request->validated();

        $movement = MovementKardex::create($data);

        return new MovementKardexResource($movement);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, MovementKardex $movementKardex)
    {
        $loadedMovement = $this->queryBuilderService->buildForModel($movementKardex, $request)
            ->firstOrFail();

        return new MovementKardexResource($loadedMovement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovementKardexRequest $request, MovementKardex $movementKardex): MovementKardexResource
    {
        $data = $request->validated();

        $movementKardex->update($data);

        return new MovementKardexResource($movementKardex);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MovementKardex $movementKardex)
    {
        $movementKardex->delete();

        return response(null, 204);
    }
}
