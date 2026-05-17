<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbortResource extends JsonResource
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
            'abort_type_id' => $this->abort_type_id,
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'abort_type' => new MilkingTypeResource($this->whenLoaded('abortType')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
        ];
    }
}
