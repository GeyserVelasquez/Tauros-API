<?php

namespace App\Http\Controllers;

use App\Models\TreatmentApplication;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use App\Http\Resources\TreatmentApplicationResource;

class TreatmentApplicationController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(TreatmentApplication::class, $request);

        $applications = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return TreatmentApplicationResource::collection($applications);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TreatmentApplication $treatmentApplication)
    {
        $loadedApplication = $this->queryBuilderService->buildForModel($treatmentApplication, $request)
            ->firstOrFail();

        return new TreatmentApplicationResource($loadedApplication);
    }

    /**
     * Mark the treatment application as completed and deduct stock.
     */
    public function apply(Request $request, TreatmentApplication $treatmentApplication)
    {
        $data = $request->validate([
            'quantity_used' => ['required', 'numeric', 'min:0.01'],
            'applied_by_id' => ['nullable', 'exists:technicians,id'],
        ]);

        $appliedBy = $data['applied_by_id'] ?? $treatmentApplication->clinicHistory?->technician_id ?? 1;
        $quantity = (int) round($data['quantity_used'] * 100);

        \DB::transaction(function () use ($treatmentApplication, $appliedBy, $quantity) {
            $treatmentApplication->apply($appliedBy, $quantity);
        });

        return new TreatmentApplicationResource($treatmentApplication->load(['livestock', 'clinicalTreatment', 'supply', 'appliedBy']));
    }

    /**
     * Revert the treatment application to scheduled (pending).
     */
    public function unapply(TreatmentApplication $treatmentApplication)
    {
        \DB::transaction(function () use ($treatmentApplication) {
            $treatmentApplication->unapply();
        });

        return new TreatmentApplicationResource($treatmentApplication->load(['livestock', 'clinicalTreatment', 'supply', 'appliedBy']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentApplication $treatmentApplication)
    {
        $treatmentApplication->delete();

        return response(null, 204);
    }
}
