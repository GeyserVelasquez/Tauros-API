<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmbrionBatch\StoreEmbrionBatchRequest;
use App\Http\Requests\EmbrionBatch\UpdateEmbrionBatchRequest;
use App\Http\Resources\EmbrionBatchResource;
use App\Models\EmbrionBatch;
use Illuminate\Http\Request;

class EmbrionBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = EmbrionBatch::all()->toResourceCollection();

        return $batches;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmbrionBatchRequest $request)
    {
        $data = $request->validated();

        $embrionBatch = EmbrionBatch::create($data);

        return new EmbrionBatchResource($embrionBatch);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmbrionBatch $embrionBatch)
    {
        return new EmbrionBatchResource($embrionBatch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmbrionBatchRequest $request, EmbrionBatch $embrionBatch)
    {
        $data = $request->validated();

        $embrionBatch->update($data);

        return new EmbrionBatchResource($embrionBatch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmbrionBatch $embrionBatch)
    {
        $embrionBatch->delete();

        return response(null, 204);
    }
}
