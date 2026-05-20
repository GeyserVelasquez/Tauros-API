<?php

namespace App\Http\Controllers;

use App\Http\Requests\Technician\UpdateTechnicianRequest;
use App\Http\Requests\Technician\StoreTechnicianRequest;
use App\Http\Resources\TechnicianResource;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $technicians = Technician::all()->toResourceCollection();

        return $technicians;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTechnicianRequest $request)
    {
        $data = $request->validated();

        $technician = Technician::create($data);

        return (new TechnicianResource($technician));
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
        return (new TechnicianResource($technician));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTechnicianRequest $request, Technician $technician): TechnicianResource
    {
        $data = $request->validated();

        $technician->update($data);

        return (new TechnicianResource($technician));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();

        return response(null, 204);
    }
}
