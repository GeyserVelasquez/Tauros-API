<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplyMovementResource extends JsonResource
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
            'supply_id' => $this->supply_id,
            'type' => $this->type,
            'made_at' => $this->made_at,
            'attributes' => $this->attributes,
        ];
    }
}
