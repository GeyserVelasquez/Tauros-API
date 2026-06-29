<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemenBatch\StoreSemenBatchRequest;
use App\Http\Requests\SemenBatch\UpdateSemenBatchRequest;
use App\Http\Resources\SemenBatchResource;
use App\Models\SemenBatch;
use Illuminate\Http\Request;

class SemenBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = SemenBatch::all()->toResourceCollection();

        return $batches;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSemenBatchRequest $request)
    {
        $data = $request->validated();

        $semenBatch = SemenBatch::create($data);

        return new SemenBatchResource($semenBatch);
    }

    /**
     * Display the specified resource.
     */
    public function show(SemenBatch $semenBatch)
    {
        return new SemenBatchResource($semenBatch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSemenBatchRequest $request, SemenBatch $semenBatch)
    {
        $data = $request->validated();

        $semenBatch->update($data);

        return new SemenBatchResource($semenBatch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SemenBatch $semenBatch)
    {
        $semenBatch->delete();

        return response(null, 204);
    }
}
