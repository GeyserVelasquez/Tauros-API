<?php

namespace App\Http\Resources;

use App\Models\Birth;
use App\Models\Milking;
use App\Models\Abort;
use App\Models\ClinicHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrowthResource extends JsonResource
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
            'weight' => $this->weight,
            'height' => $this->height,
            'made_at' => $this->made_at?->format('Y-m-d'),
            'livestock_id' => $this->livestock_id,
            'growth_type_id' => $this->growth_type_id,
            'technician_id' => $this->technician_id,
            'growthable_type' => $this->growthable_type,
            'growthable_id' => $this->growthable_id,

            'livestock' => new LivestockResource($this->whenLoaded('livestock')),
            'growth_type' => new GrowthTypeResource($this->whenLoaded('growthType')),
            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'growthable' => $this->whenLoaded('growthable', fn () => $this->resolveGrowthableResource($this->growthable)),
        ];
    }

    /**
     * Resuelve el Resource adecuado para el polimorfismo growthable.
     */
    private function resolveGrowthableResource(mixed $growthable): mixed
    {
        $map = [
            Birth::class => BirthResource::class,
            Milking::class => MilkingResource::class,
            Abort::class => AbortResource::class,
            ClinicHistory::class => ClinicHistoryResource::class,
        ];

        $resourceClass = $map[get_class($growthable)] ?? null;

        return $resourceClass ? new $resourceClass($growthable) : $growthable;
    }
}
