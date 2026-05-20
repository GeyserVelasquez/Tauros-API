<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkingResource extends JsonResource
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
            'technician_id' => $this->technician_id,
            'made_at' => $this->made_at->format('Y-m-d'),
            'milking_type_id' => $this->milking_type_id,
            'first_weight' => $this->first_weight,
            'second_weight' => $this->second_weight,
            'third_weight' => $this->third_weight,
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'milking_type' => new MilkingTypeResource($this->whenLoaded('milkingType')),
        ];
    }
}
