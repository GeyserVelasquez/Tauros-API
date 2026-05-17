<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeasingResource extends JsonResource
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
            'detected_at' => $this->detected_at->format('Y-m-d'),
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
        ];
    }
}
