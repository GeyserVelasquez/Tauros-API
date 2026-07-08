<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeathCause\StoreDeathCauseRequest;
use App\Http\Requests\DeathCause\UpdateDeathCauseRequest;
use App\Http\Resources\DeathCauseResource;
use App\Models\DeathCause;

class DeathCauseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deathCauses = DeathCause::all()->toResourceCollection();

        return $deathCauses;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeathCauseRequest $request)
    {
        $data = $request->validated();

        $deathCause = DeathCause::create($data);

        return new DeathCauseResource($deathCause);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeathCause $deathCause)
    {
        return new DeathCauseResource($deathCause);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeathCauseRequest $request, DeathCause $deathCause): DeathCauseResource
    {
        $data = $request->validated();

        $deathCause->update($data);

        return new DeathCauseResource($deathCause);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeathCause $deathCause)
    {
        $deathCause->delete();

        return response(null, 204);
    }
}
