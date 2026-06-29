<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(Service::class, $request);

        $services = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();

        $service = Service::create($data);

        return new ServiceResource($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Service $service)
    {
        $loadedService = $this->queryBuilderService->buildForModel($service, $request)
            ->firstOrFail();

        return new ServiceResource($loadedService);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service): ServiceResource
    {
        $data = $request->validated();

        $service->update($data);

        return new ServiceResource($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return response(null, 204);
    }
}
