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
            'outcome_type_id' => $this->outcome_type_id,
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'outcome_type' => new OutcomeTypeResource($this->whenLoaded('outcomeType')),
        ];
    }
}
