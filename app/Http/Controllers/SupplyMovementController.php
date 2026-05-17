<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplyMovement\StoreSupplyMovementRequest;
use App\Http\Requests\SupplyMovement\UpdateSupplyMovementRequest;
use App\Http\Resources\SupplyMovementResource;
use App\Models\SupplyMovement;
use Illuminate\Http\Request;

class SupplyMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = SupplyMovement::all()->toResourceCollection();

        return $movements;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplyMovementRequest $request)
    {
        $data = $request->validated();

        $supplyMovement = SupplyMovement::create($data);

        return new SupplyMovementResource($supplyMovement);
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplyMovement $supplyMovement)
    {
        return new SupplyMovementResource($supplyMovement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplyMovementRequest $request, SupplyMovement $supplyMovement)
    {
        $data = $request->validated();

        $supplyMovement->update($data);

        return new SupplyMovementResource($supplyMovement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplyMovement $supplyMovement)
    {
        $supplyMovement->delete();

        return response(null, 204);
    }
}
