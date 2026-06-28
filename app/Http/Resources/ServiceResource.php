<?php

namespace App\Http\Resources;

use App\Models\EmbrionBatch;
use App\Models\Livestock;
use App\Models\SemenBatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'female_id' => $this->female_id,
            'parentable_type' => $this->parentable_type,
            'parentable_id' => $this->parentable_id,
            'technician_id' => $this->technician_id,
            'service_type_id' => $this->service_type_id,
            'made_at' => $this->made_at?->format('Y-m-d'),

            'female' => new LivestockResource($this->whenLoaded('female')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'service_type' => new ServiceTypeResource($this->whenLoaded('serviceType')),
            'parentable' => $this->whenLoaded('parentable', fn () => $this->resolveParentableResource($this->parentable)),
        ];
    }

    /**
     * Resuelve el Resource adecuado para el polimorfismo parentable.
     */
    private function resolveParentableResource(mixed $parentable): mixed
    {
        $map = [
            Livestock::class => LivestockResource::class,
            SemenBatch::class => SemenBatchResource::class,
            EmbrionBatch::class => EmbrionBatchResource::class,
        ];

        $resourceClass = $map[get_class($parentable)] ?? null;

        return $resourceClass ? new $resourceClass($parentable) : $parentable;
    }
}
