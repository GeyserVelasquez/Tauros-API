<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockResource extends JsonResource
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
            'brand_number' => $this->brand_number,
            'electronic_code' => $this->electronic_code,
            'name' => $this->name,
            'entry_date' => $this->entry_date?->format('Y-m-d'),
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'general_comment' => $this->general_comment,
            'tits' => $this->tits,
            'is_enabled' => (bool)$this->is_enabled,
            'is_alive' => (bool)$this->is_alive,
            'animal_category' => $this->animal_category?->value,
            'entry_cause_id' => $this->entry_cause_id,
            'state_id' => $this->state_id,
            'breed_id' => $this->breed_id,
            'color_id' => $this->color_id,
            'classification_id' => $this->classification_id,
            'owner_id' => $this->owner_id,
            'technician_id' => $this->technician_id,
            'father_id' => $this->father_id,
            'mother_id' => $this->mother_id,
            'adoptive_mother_id' => $this->adoptive_mother_id,
            'receiving_mother_id' => $this->receiving_mother_id,

            'breed' => new BreedResource($this->whenLoaded('breed')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'classification' => new ClassificationResource($this->whenLoaded('classification')),

            'father' => new LivestockResource($this->whenLoaded('father')),
            'mother' => new LivestockResource($this->whenLoaded('mother')),
            'adoptive_mother' => new LivestockResource($this->whenLoaded('adoptive_mother')),
            'receiving_mother' => new LivestockResource($this->whenLoaded('receiving_mother')),

            'owner' => new OwnerResource($this->whenLoaded('owner')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'entry_cause' => new EntryCauseResource($this->whenLoaded('entryCause')),
            'state' => new StateResource($this->whenLoaded('state')),

            'batch' => new BatchResource($this->whenLoaded('currentBatchMovement')),

        ];
    }
}
