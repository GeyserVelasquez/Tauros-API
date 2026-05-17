<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicDiagnostic\StoreClinicDiagnosticRequest;
use App\Http\Requests\ClinicDiagnostic\UpdateClinicDiagnosticRequest;
use App\Http\Resources\ClinicDiagnosticResource;
use App\Models\ClinicDiagnostic;
use Illuminate\Http\Request;

class ClinicDiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diagnostics = ClinicDiagnostic::all()->toResourceCollection();

        return $diagnostics;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicDiagnosticRequest $request)
    {
        $data = $request->validated();

        $clinicDiagnostic = ClinicDiagnostic::create($data);

        return new ClinicDiagnosticResource($clinicDiagnostic);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClinicDiagnostic $clinicDiagnostic)
    {
        return new ClinicDiagnosticResource($clinicDiagnostic);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClinicDiagnosticRequest $request, ClinicDiagnostic $clinicDiagnostic)
    {
        $data = $request->validated();

        $clinicDiagnostic->update($data);

        return new ClinicDiagnosticResource($clinicDiagnostic);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicDiagnostic $clinicDiagnostic)
    {
        $clinicDiagnostic->delete();

        return response(null, 204);
    }
}
