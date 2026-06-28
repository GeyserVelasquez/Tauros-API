<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BirthResource extends JsonResource
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
            'mother_id' => $this->mother_id,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'postbirth_revision_date' => $this->postbirth_revision_date?->format('Y-m-d'),
            'birth_type_id' => $this->birth_type_id,
            'technician_id' => $this->technician_id,

            'mother' => new LivestockResource($this->whenLoaded('mother')),
            'birth_type' => new BirthTypeResource($this->whenLoaded('birthType')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'newborns' => NewbornResource::collection($this->whenLoaded('newborns')),
        ];
    }
}
