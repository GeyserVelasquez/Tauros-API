<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicHistoryResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'attributes' => $this->attributes,
            'livestock_id' => $this->livestock_id,
            'technician_id' => $this->technician_id,

            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'clinic_diagnostics' => ClinicDiagnosticResource::collection($this->whenLoaded('clinicDiagnostics')),
            'clinical_treatments' => ClinicalTreatmentResource::collection($this->whenLoaded('clinicalTreatments')),
        ];
    }
}
