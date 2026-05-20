<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmbrionBatchResource extends JsonResource
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
            'mother_id' => $this->mother_id,
            'father_id' => $this->father_id,
            'technician_id' => $this->technician_id,
            'mother' => new LivestockResource($this->whenLoaded('mother')),
            'father' => new LivestockResource($this->whenLoaded('father')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
        ];
    }
}
