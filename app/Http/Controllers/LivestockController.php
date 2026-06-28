<?php

namespace App\Http\Controllers;

use App\Http\Requests\Livestock\StoreLivestockRequest;
use App\Http\Requests\Livestock\UpdateLivestockRequest;
use App\Http\Resources\LivestockResource;
use App\Models\Livestock;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class LivestockController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Livestock::class, $request);

        $livestock = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return LivestockResource::collection($livestock);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivestockRequest $request): LivestockResource
    {
        $data = $request->validated();

        $livestock = Livestock::create($data);

        return new LivestockResource($livestock);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Livestock $livestock): LivestockResource
    {
        $loadedLivestock = $this->queryBuilderService->buildForModel($livestock, $request)
            ->firstOrFail();

        return new LivestockResource($loadedLivestock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLivestockRequest $request, Livestock $livestock): LivestockResource
    {
        $data = $request->validated();

        $livestock->update($data);

        return new LivestockResource($livestock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livestock $livestock)
    {
        $livestock->delete();

        return response(null, 204);
    }
}
