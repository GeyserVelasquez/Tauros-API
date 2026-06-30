<?php

namespace App\Http\Controllers;

use App\Http\Requests\Abort\StoreAbortRequest;
use App\Http\Requests\Abort\UpdateAbortRequest;
use App\Http\Resources\AbortResource;
use App\Models\Abort;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class AbortController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Abort::class, $request);

        $aborts = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return AbortResource::collection($aborts);
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
