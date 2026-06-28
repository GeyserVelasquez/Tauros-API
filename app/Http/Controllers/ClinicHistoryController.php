<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicHistory\StoreClinicHistoryRequest;
use App\Http\Requests\ClinicHistory\UpdateClinicHistoryRequest;
use App\Http\Resources\ClinicHistoryResource;
use App\Models\ClinicHistory;
use Illuminate\Http\Request;

class ClinicHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinicHistories = ClinicHistory::all()->toResourceCollection();

        return $clinicHistories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicHistoryRequest $request)
    {
        $data = $request->validated();

        $clinicHistory = ClinicHistory::create($data);

        return (new ClinicHistoryResource($clinicHistory));
    }

    /**
     * Display the specified resource.
     */
    public function show(ClinicHistory $clinicHistory)
    {
        return (new ClinicHistoryResource($clinicHistory));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClinicHistoryRequest $request, ClinicHistory $clinicHistory): ClinicHistoryResource
    {
        $data = $request->validated();

        $clinicHistory->update($data);

        return (new ClinicHistoryResource($clinicHistory));
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
