<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all()->toResourceCollection();

        return $services;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();

        $service = Service::create($data);

        return (new ServiceResource($service));
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return (new ServiceResource($service));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service): ServiceResource
    {
        $data = $request->validated();

        $service->update($data);

        return (new ServiceResource($service));
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
