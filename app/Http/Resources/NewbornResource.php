<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewbornResource extends JsonResource
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
            'birth_id' => $this->birth_id,
            'newborn_type_id' => $this->newborn_type_id,
            'livestock_id' => $this->livestock_id,

            'birth' => new BirthResource($this->whenLoaded('birth')),
            'newborn_type' => new NewbornTypeResource($this->whenLoaded('newbornType')),
            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
        ];
    }
}
