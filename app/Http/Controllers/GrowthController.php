<?php

namespace App\Http\Controllers;

use App\Http\Requests\Growth\StoreGrowthRequest;
use App\Http\Requests\Growth\UpdateGrowthRequest;
use App\Http\Resources\GrowthResource;
use App\Models\Growth;
use Illuminate\Http\Request;

class GrowthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $growths = Growth::all()->toResourceCollection();

        return $growths;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrowthRequest $request)
    {
        $data = $request->validated();

        $growth = Growth::create($data);

        return (new GrowthResource($growth));
    }

    /**
     * Display the specified resource.
     */
    public function show(Growth $growth)
    {
        return (new GrowthResource($growth));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrowthRequest $request, Growth $growth): GrowthResource
    {
        $data = $request->validated();

        $growth->update($data);

        return (new GrowthResource($growth));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Growth $growth)
    {
        $growth->delete();

        return response(null, 204);
    }
}
