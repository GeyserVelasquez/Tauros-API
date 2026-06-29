<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtractionType\StoreExtractionTypeRequest;
use App\Http\Requests\ExtractionType\UpdateExtractionTypeRequest;
use App\Http\Resources\ExtractionTypeResource;
use App\Models\ExtractionType;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class ExtractionTypeController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(ExtractionType::class, $request);

        $extractionTypes = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return ExtractionTypeResource::collection($extractionTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExtractionTypeRequest $request)
    {
        $data = $request->validated();

        $extractionType = ExtractionType::create($data);

        return new ExtractionTypeResource($extractionType);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ExtractionType $extractionType)
    {
        $loadedExtractionType = $this->queryBuilderService->buildForModel($extractionType, $request)
            ->firstOrFail();

        return new ExtractionTypeResource($loadedExtractionType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExtractionTypeRequest $request, ExtractionType $extractionType): ExtractionTypeResource
    {
        $data = $request->validated();

        $extractionType->update($data);

        return new ExtractionTypeResource($extractionType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExtractionType $extractionType)
    {
        $extractionType->delete();

        return response(null, 204);
    }
}
