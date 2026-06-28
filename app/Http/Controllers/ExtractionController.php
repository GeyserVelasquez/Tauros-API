<?php

namespace App\Http\Controllers;

use App\Http\Requests\Extraction\StoreExtractionRequest;
use App\Http\Requests\Extraction\UpdateExtractionRequest;
use App\Http\Resources\ExtractionResource;
use App\Models\Extraction;
use Illuminate\Http\Request;

class ExtractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $extractions = Extraction::all()->toResourceCollection();

        return $extractions;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExtractionRequest $request)
    {
        $data = $request->validated();

        $extraction = Extraction::create($data);

        return (new ExtractionResource($extraction));
    }

    /**
     * Display the specified resource.
     */
    public function show(Extraction $extraction)
    {
        return (new ExtractionResource($extraction));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExtractionRequest $request, Extraction $extraction): ExtractionResource
    {
        $data = $request->validated();

        $extraction->update($data);

        return (new ExtractionResource($extraction));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Extraction $extraction)
    {
        $extraction->delete();

        return response(null, 204);
    }
}
