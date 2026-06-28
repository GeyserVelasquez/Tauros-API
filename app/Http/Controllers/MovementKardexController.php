<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementKardex\StoreMovementKardexRequest;
use App\Http\Requests\MovementKardex\UpdateMovementKardexRequest;
use App\Http\Resources\MovementKardexResource;
use App\Models\MovementKardex;
use Illuminate\Http\Request;

class MovementKardexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = MovementKardex::all()->toResourceCollection();

        return $movements;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovementKardexRequest $request)
    {
        $data = $request->validated();

        $movement = MovementKardex::create($data);

        return (new MovementKardexResource($movement));
    }

    /**
     * Display the specified resource.
     */
    public function show(MovementKardex $movementKardex)
    {
        return (new MovementKardexResource($movementKardex));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovementKardexRequest $request, MovementKardex $movementKardex): MovementKardexResource
    {
        $data = $request->validated();

        $movementKardex->update($data);

        return (new MovementKardexResource($movementKardex));
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
