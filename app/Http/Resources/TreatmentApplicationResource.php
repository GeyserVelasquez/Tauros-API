<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'livestock_id' => $this->livestock_id,
            'clinical_treatment_id' => $this->clinical_treatment_id,
            'supply_id' => $this->supply_id,
            'dose_number' => $this->dose_number,
            'quantity' => $this->quantity, // stored as quantity * 100
            'quantity_formatted' => round($this->quantity / 100, 2), // helper to get decimal form
            'scheduled_date' => $this->scheduled_date?->toIso8601String(),
            'applied_at' => $this->applied_at?->toIso8601String(),
            'applied_by_id' => $this->applied_by_id,
            'clinic_history_id' => $this->clinic_history_id,
            'sanitary_plan_id' => $this->sanitary_plan_id,

            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'clinical_treatment' => new ClinicalTreatmentResource($this->whenLoaded('clinicalTreatment')),
            'supply' => new SupplyResource($this->whenLoaded('supply')),
            'applied_by' => new TechnicianResource($this->whenLoaded('appliedBy')),
            'clinic_history' => new ClinicHistoryResource($this->whenLoaded('clinicHistory')),
            'sanitary_plan' => $this->whenLoaded('sanitaryPlan'),
        ];
    }
}
