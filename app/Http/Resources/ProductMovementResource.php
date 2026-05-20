<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMovementResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'type' => $this->type,
            'made_at' => $this->made_at,
            'attributes' => $this->attributes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
