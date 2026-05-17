<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicalTreatment\StoreClinicalTreatmentRequest;
use App\Http\Requests\ClinicalTreatment\UpdateClinicalTreatmentRequest;
use App\Http\Resources\ClinicalTreatmentResource;
use App\Models\ClinicalTreatment;
use Illuminate\Http\Request;

class ClinicalTreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treatments = ClinicalTreatment::all()->toResourceCollection();

        return $treatments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicalTreatmentRequest $request)
    {
        $data = $request->validated();

        $clinicalTreatment = ClinicalTreatment::create($data);

        return new ClinicalTreatmentResource($clinicalTreatment);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClinicalTreatment $clinicalTreatment)
    {
        return new ClinicalTreatmentResource($clinicalTreatment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClinicalTreatmentRequest $request, ClinicalTreatment $clinicalTreatment)
    {
        $data = $request->validated();

        $clinicalTreatment->update($data);

        return new ClinicalTreatmentResource($clinicalTreatment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicalTreatment $clinicalTreatment)
    {
        $clinicalTreatment->delete();

        return response(null, 204);
    }
}
