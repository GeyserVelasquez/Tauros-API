<?php

namespace App\Http\Controllers;

use App\Http\Requests\Outcome\StoreOutcomeRequest;
use App\Http\Requests\Outcome\UpdateOutcomeRequest;
use App\Http\Resources\OutcomeResource;
use App\Models\Outcome;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outcomes = Outcome::all()->toResourceCollection();

        return $outcomes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutcomeRequest $request)
    {
        $data = $request->validated();

        $outcome = Outcome::create($data);

        return new OutcomeResource($outcome);
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome)
    {
        return new OutcomeResource($outcome);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutcomeRequest $request, Outcome $outcome)
    {
        $data = $request->validated();

        $outcome->update($data);

        return new OutcomeResource($outcome);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome)
    {
        $outcome->delete();

        return response(null, 204);
    }
}
