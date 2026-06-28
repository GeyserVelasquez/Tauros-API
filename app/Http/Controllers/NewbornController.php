<?php

namespace App\Http\Controllers;

use App\Http\Requests\Newborn\StoreNewbornRequest;
use App\Http\Requests\Newborn\UpdateNewbornRequest;
use App\Http\Resources\NewbornResource;
use App\Models\Newborn;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class NewbornController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Newborn::class, $request);

        $newborns = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return NewbornResource::collection($newborns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewbornRequest $request)
    {
        $data = $request->validated();

        $newborn = Newborn::create($data);

        return new NewbornResource($newborn);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Newborn $newborn)
    {
        $loadedNewborn = $this->queryBuilderService->buildForModel($newborn, $request)
            ->firstOrFail();

        return new NewbornResource($loadedNewborn);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewbornRequest $request, Newborn $newborn): NewbornResource
    {
        $data = $request->validated();

        $newborn->update($data);

        return new NewbornResource($newborn);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newborn $newborn)
    {
        $newborn->delete();

        return response(null, 204);
    }
}
