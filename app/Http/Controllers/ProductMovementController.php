<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductMovement\StoreProductMovementRequest;
use App\Http\Requests\ProductMovement\UpdateProductMovementRequest;
use App\Http\Resources\ProductMovementResource;
use App\Models\ProductMovement;
use Illuminate\Http\Request;

class ProductMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = ProductMovement::all()->toResourceCollection();

        return $movements;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductMovementRequest $request)
    {
        $data = $request->validated();

        $productMovement = ProductMovement::create($data);

        return new ProductMovementResource($productMovement);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductMovement $productMovement)
    {
        return new ProductMovementResource($productMovement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductMovementRequest $request, ProductMovement $productMovement)
    {
        $data = $request->validated();

        $productMovement->update($data);

        return new ProductMovementResource($productMovement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductMovement $productMovement)
    {
        $productMovement->delete();

        return response(null, 204);
    }
}
