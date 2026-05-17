<?php

namespace App\Http\Controllers;

use App\Http\Requests\Abort\StoreAbortRequest;
use App\Http\Requests\Abort\UpdateAbortRequest;
use App\Http\Resources\AbortResource;
use App\Models\Abort;
use Illuminate\Http\Request;

class AbortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aborts = Abort::all()->toResourceCollection();

        return $aborts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbortRequest $request)
    {
        $data = $request->validated();

        $abort = Abort::register($data);

        return new AbortResource($abort);
    }

    /**
     * Display the specified resource.
     */
    public function show(Abort $abort)
    {
        return new AbortResource($abort);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbortRequest $request, Abort $abort)
    {
        $data = $request->validated();

        $abort->amend($data);

        return new AbortResource($abort);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Abort $abort)
    {
        $abort->delete();

        return response(null, 204);
    }
}
