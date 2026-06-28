<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicHistory\StoreClinicHistoryRequest;
use App\Http\Requests\ClinicHistory\UpdateClinicHistoryRequest;
use App\Http\Resources\ClinicHistoryResource;
use App\Models\ClinicHistory;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class ClinicHistoryController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(ClinicHistory::class, $request);

        $clinicHistories = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return ClinicHistoryResource::collection($clinicHistories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicHistoryRequest $request)
    {
        $data = $request->validated();

        $clinicHistory = ClinicHistory::create($data);

        return new ClinicHistoryResource($clinicHistory);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ClinicHistory $clinicHistory)
    {
        $loadedClinicHistory = $this->queryBuilderService->buildForModel($clinicHistory, $request)
            ->firstOrFail();

        return new ClinicHistoryResource($loadedClinicHistory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClinicHistoryRequest $request, ClinicHistory $clinicHistory): ClinicHistoryResource
    {
        $data = $request->validated();

        $clinicHistory->update($data);

        return new ClinicHistoryResource($clinicHistory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicHistory $clinicHistory)
    {
        $clinicHistory->delete();

        return response(null, 204);
    }
}
