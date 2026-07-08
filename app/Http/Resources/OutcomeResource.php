<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutcomeResource extends JsonResource
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
            'made_at' => $this->made_at->format('Y-m-d'),
            'outcome_type' => $this->outcome_type,
            'death_cause_id' => $this->death_cause_id,
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'death_cause' => new DeathCauseResource($this->whenLoaded('deathCause')),
        ];
    }
}
