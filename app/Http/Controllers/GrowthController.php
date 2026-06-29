<?php

namespace App\Http\Controllers;

use App\Http\Requests\Growth\StoreGrowthRequest;
use App\Http\Requests\Growth\UpdateGrowthRequest;
use App\Http\Resources\GrowthResource;
use App\Models\Growth;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class GrowthController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Growth::class, $request);

        $growths = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return GrowthResource::collection($growths);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrowthRequest $request)
    {
        $data = $request->validated();

        $growth = Growth::create($data);

        return new GrowthResource($growth);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Growth $growth)
    {
        $loadedGrowth = $this->queryBuilderService->buildForModel($growth, $request)
            ->firstOrFail();

        return new GrowthResource($loadedGrowth);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrowthRequest $request, Growth $growth): GrowthResource
    {
        $data = $request->validated();

        $growth->update($data);

        return new GrowthResource($growth);
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
