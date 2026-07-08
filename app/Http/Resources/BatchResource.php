<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
            'paddock_id' => $this->paddock_id,
            'paddock' => new PaddockResource($this->whenLoaded('paddock')),
            'livestock' => LivestockResource::collection($this->whenLoaded('livestock')),
        ];
    }
}
