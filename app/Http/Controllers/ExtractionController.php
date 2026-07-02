<?php

namespace App\Http\Controllers;

use App\Http\Requests\Extraction\StoreExtractionRequest;
use App\Http\Requests\Extraction\UpdateExtractionRequest;
use App\Http\Resources\ExtractionResource;
use App\Models\Extraction;
use App\Services\QueryBuilderService;
use App\Services\ExtractionRegistrationService;
use Illuminate\Http\Request;

class ExtractionController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService,
        protected ExtractionRegistrationService $registrationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Extraction::class, $request);

        $extractions = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return ExtractionResource::collection($extractions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExtractionRequest $request)
    {
        $data = $request->validated();

        $extraction = $this->registrationService->register($data);

        return new ExtractionResource($extraction);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Extraction $extraction)
    {
        $loadedExtraction = $this->queryBuilderService->buildForModel($extraction, $request)
            ->firstOrFail();

        return new ExtractionResource($loadedExtraction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExtractionRequest $request, Extraction $extraction): ExtractionResource
    {
        $data = $request->validated();

        $updatedExtraction = $this->registrationService->update($extraction, $data);

        return new ExtractionResource($updatedExtraction);
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
