<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicHistory\StoreClinicHistoryRequest;
use App\Http\Requests\ClinicHistory\UpdateClinicHistoryRequest;
use App\Http\Resources\ClinicHistoryResource;
use App\Models\ClinicHistory;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class ClinicHistoryController extends Controller
{
    public function __construct(
        protected QueryBuilderService $queryBuilderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->queryBuilderService->build(ClinicHistory::class, $request);

        $clinicHistories = $query->paginate($request->get('per_page', 15))
            ->withQueryString();

        return ClinicHistoryResource::collection($clinicHistories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicHistoryRequest $request)
    {
        $data = $request->validated();

        $clinicHistory = \DB::transaction(function () use ($data) {
            $clinicHistory = ClinicHistory::create($data);

            if (isset($data['diagnostics'])) {
                $clinicHistory->clinicDiagnostics()->sync($data['diagnostics']);
            }

            if (isset($data['treatments'])) {
                foreach ($data['treatments'] as $treatment) {
                    $clinicHistory->clinicalTreatments()->attach($treatment['clinical_treatment_id']);

                    $isRecurring = $treatment['is_recurring'] ?? false;
                    $totalDoses = $isRecurring ? ($treatment['total_doses'] ?? 1) : 1;
                    $frequencyHours = $isRecurring ? ($treatment['frequency_hours'] ?? 24) : 24;

                    for ($i = 1; $i <= $totalDoses; $i++) {
                        $scheduledDate = now()->addHours(($i - 1) * $frequencyHours);
                        
                        $app = \App\Models\TreatmentApplication::create([
                            'livestock_id' => $clinicHistory->livestock_id,
                            'clinical_treatment_id' => $treatment['clinical_treatment_id'],
                            'supply_id' => $treatment['supply_id'] ?? null,
                            'dose_number' => $isRecurring ? $i : null,
                            'quantity' => (int) round($treatment['quantity'] * 100),
                            'scheduled_date' => $scheduledDate,
                            'clinic_history_id' => $clinicHistory->id,
                        ]);

                        if ($i === 1) {
                            $app->apply(
                                $clinicHistory->technician_id ?? auth()->id() ?? 1,
                                (int) round($treatment['quantity'] * 100)
                            );
                        }
                    }
                }
            }

            return $clinicHistory;
        });

        return new ClinicHistoryResource($clinicHistory->load(['livestock', 'technician', 'clinicDiagnostics', 'clinicalTreatments', 'treatmentApplications']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ClinicHistory $clinicHistory)
    {
        $loadedClinicHistory = $this->queryBuilderService->buildForModel($clinicHistory, $request)
            ->firstOrFail();

        return new ClinicHistoryResource($loadedClinicHistory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClinicHistoryRequest $request, ClinicHistory $clinicHistory): ClinicHistoryResource
    {
        $data = $request->validated();

        \DB::transaction(function () use ($clinicHistory, $data) {
            $clinicHistory->update($data);

            if (isset($data['diagnostics'])) {
                $clinicHistory->clinicDiagnostics()->sync($data['diagnostics']);
            }

            if (isset($data['treatments'])) {
                // Delete scheduled applications that are NOT applied yet
                $clinicHistory->treatmentApplications()->whereNull('applied_at')->delete();
                $clinicHistory->clinicalTreatments()->detach();

                foreach ($data['treatments'] as $treatment) {
                    $clinicHistory->clinicalTreatments()->attach($treatment['clinical_treatment_id']);

                    $isRecurring = $treatment['is_recurring'] ?? false;
                    $totalDoses = $isRecurring ? ($treatment['total_doses'] ?? 1) : 1;
                    $frequencyHours = $isRecurring ? ($treatment['frequency_hours'] ?? 24) : 24;

                    for ($i = 1; $i <= $totalDoses; $i++) {
                        // Check if we already have an applied dose for this treatment
                        $alreadyApplied = $clinicHistory->treatmentApplications()
                            ->where('clinical_treatment_id', $treatment['clinical_treatment_id'])
                            ->where('dose_number', $isRecurring ? $i : null)
                            ->whereNotNull('applied_at')
                            ->exists();

                        if ($alreadyApplied) {
                            continue;
                        }

                        $scheduledDate = now()->addHours(($i - 1) * $frequencyHours);

                        $app = \App\Models\TreatmentApplication::create([
                            'livestock_id' => $clinicHistory->livestock_id,
                            'clinical_treatment_id' => $treatment['clinical_treatment_id'],
                            'supply_id' => $treatment['supply_id'] ?? null,
                            'dose_number' => $isRecurring ? $i : null,
                            'quantity' => (int) round($treatment['quantity'] * 100),
                            'scheduled_date' => $scheduledDate,
                            'clinic_history_id' => $clinicHistory->id,
                        ]);

                        if ($i === 1) {
                            $app->apply(
                                $clinicHistory->technician_id ?? auth()->id() ?? 1,
                                (int) round($treatment['quantity'] * 100)
                            );
                        }
                    }
                }
            }
        });

        return new ClinicHistoryResource($clinicHistory->load(['livestock', 'technician', 'clinicDiagnostics', 'clinicalTreatments', 'treatmentApplications']));
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
