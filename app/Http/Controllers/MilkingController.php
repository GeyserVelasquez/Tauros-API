<?php

namespace App\Http\Controllers;

use App\Http\Requests\Milking\StoreMilkingRequest;
use App\Http\Requests\Milking\UpdateMilkingRequest;
use App\Http\Resources\MilkingResource;
use App\Models\Milking;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class MilkingController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Milking::class, $request);

        $milkings = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return MilkingResource::collection($milkings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMilkingRequest $request)
    {
        $data = $request->validated();

        $milking = Milking::create($data);

        return new MilkingResource($milking);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Milking $milking)
    {
        $loadedMilking = $this->queryBuilderService->buildForModel($milking, $request)
            ->firstOrFail();

        return new MilkingResource($loadedMilking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMilkingRequest $request, Milking $milking)
    {
        $data = $request->validated();

        $milking->update($data);

        return new MilkingResource($milking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Milking $milking)
    {
        $milking->delete();

        return response(null, 204);
    }
}
