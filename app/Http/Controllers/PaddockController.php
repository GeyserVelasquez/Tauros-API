<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paddock\StorePaddockRequest;
use App\Http\Requests\Paddock\UpdatePaddockRequest;
use App\Http\Resources\PaddockResource;
use App\Models\Paddock;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class PaddockController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Paddock::class, $request);

        $paddocks = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return PaddockResource::collection($paddocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaddockRequest $request): PaddockResource
    {
        $data = $request->validated();

        $paddock = Paddock::create($data);

        return new PaddockResource($paddock);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Paddock $paddock): PaddockResource
    {
        $loadedPaddock = $this->queryBuilderService->buildForModel($paddock, $request)
            ->firstOrFail();

        return new PaddockResource($loadedPaddock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaddockRequest $request, Paddock $paddock): PaddockResource
    {
        $data = $request->validated();

        $paddock->update($data);

        return new PaddockResource($paddock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paddock $paddock)
    {
        $paddock->delete();

        return response(null, 204);
    }
}
