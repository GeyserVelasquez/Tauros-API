<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisionResource extends JsonResource
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
            'revision_result' => $this->revision_result,
            'revision_type_id' => $this->revision_type_id,
            'technician_id' => $this->technician_id,
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'revision_type' => new RevisionTypeResource($this->whenLoaded('revisionType')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
