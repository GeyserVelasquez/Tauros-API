<?php

namespace App\Http\Controllers;

use App\Http\Requests\Birth\StoreBirthRequest;
use App\Http\Requests\Birth\UpdateBirthRequest;
use App\Http\Resources\BirthResource;
use App\Models\Birth;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class BirthController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Birth::class, $request);

        $births = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return BirthResource::collection($births);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBirthRequest $request)
    {
        $data = $request->validated();

        $birth = Birth::create($data);

        return new BirthResource($birth);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Birth $birth)
    {
        $loadedBirth = $this->queryBuilderService->buildForModel($birth, $request)
            ->firstOrFail();

        return new BirthResource($loadedBirth);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBirthRequest $request, Birth $birth): BirthResource
    {
        $data = $request->validated();

        $birth->update($data);

        return new BirthResource($birth);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Birth $birth)
    {
        $birth->delete();

        return response(null, 204);
    }
}
