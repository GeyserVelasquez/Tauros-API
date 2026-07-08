<?php

namespace App\Http\Controllers;

use App\Http\Requests\Outcome\StoreOutcomeRequest;
use App\Http\Requests\Outcome\UpdateOutcomeRequest;
use App\Http\Resources\OutcomeResource;
use App\Models\Outcome;
use App\Services\QueryBuilderService;
use App\Services\OutcomeRegistrationService;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService,
        protected OutcomeRegistrationService $outcomeRegistrationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Outcome::class, $request);

        $outcomes = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return OutcomeResource::collection($outcomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutcomeRequest $request)
    {
        $data = $request->validated();

        $outcome = $this->outcomeRegistrationService->register($data);

        return new OutcomeResource($outcome);
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome)
    {
        return new OutcomeResource($outcome);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutcomeRequest $request, Outcome $outcome)
    {
        $data = $request->validated();

        $outcome = $this->outcomeRegistrationService->update($outcome, $data);

        return new OutcomeResource($outcome);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome)
    {
        $this->outcomeRegistrationService->delete($outcome);

        return response(null, 204);
    }
}
