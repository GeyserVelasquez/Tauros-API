<?php

namespace App\Http\Controllers;

use App\Http\Requests\Batch\UpdateBatchRequest;
use App\Http\Requests\Batch\StoreBatchRequest;
use App\Http\Requests\Batch\MoveBatchRequest;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\Paddock;
use App\Services\QueryBuilderService;
use App\Services\BatchMovementService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BatchController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService,
        protected BatchMovementService $batchMovementService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Batch::class, $request);

        $batches = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return BatchResource::collection($batches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBatchRequest $request)
    {
        $data = $request->validated();

        $batch = Batch::create($data);

        return new BatchResource($batch);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Batch $batch)
    {
        $loadedBatch = $this->queryBuilderService->buildForModel($batch, $request)
            ->firstOrFail();

        return new BatchResource($loadedBatch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBatchRequest $request, Batch $batch): BatchResource
    {
        $data = $request->validated();

        $batch->update($data);

        return new BatchResource($batch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return response(null, 204);
    }

    /**
     * Move the batch and its livestock to a paddock.
     */
    public function move(MoveBatchRequest $request, Batch $batch): BatchResource
    {
        $paddock = Paddock::findOrFail($request->input('paddock_id'));
        $madeAt = Carbon::parse($request->input('made_at'));

        $this->batchMovementService->moveToPaddock($batch, $paddock, $madeAt);

        // Load relations requested by client or default paddock
        $loadedBatch = $this->queryBuilderService->buildForModel($batch, $request)
            ->firstOrFail();

        return new BatchResource($loadedBatch);
    }
}
