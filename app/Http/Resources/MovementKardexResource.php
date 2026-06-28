<?php

namespace App\Http\Resources;

use App\Models\Supply;
use App\Models\Product;
use App\Models\EmbrionBatch;
use App\Models\SemenBatch;
use App\Models\Livestock;
use App\Models\ClinicHistory;
use App\Models\SupplyMovement;
use App\Models\Outcome;
use App\Models\ProductMovement;
use App\Models\Extraction;
use App\Models\Service;
use App\Models\Birth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementKardexResource extends JsonResource
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
            'item_type' => $this->item_type,
            'item_id' => $this->item_id,
            'type' => $this->type?->value,
            'type_label' => $this->type?->label(),
            'quantity' => $this->quantity,
            'event_type' => $this->event_type,
            'event_id' => $this->event_id,
            'date' => $this->date?->format('Y-m-d H:i:s'),

            'item' => $this->whenLoaded('item', fn () => $this->resolveItemResource($this->item)),
            'event' => $this->whenLoaded('event', fn () => $this->resolveEventResource($this->event)),
        ];
    }

    private function resolveItemResource(mixed $item): mixed
    {
        $map = [
            Supply::class => SupplyResource::class,
            Product::class => ProductResource::class,
            EmbrionBatch::class => EmbrionBatchResource::class,
            SemenBatch::class => SemenBatchResource::class,
            Livestock::class => LivestockResource::class,
        ];

        $resourceClass = $map[get_class($item)] ?? null;

        return $resourceClass ? new $resourceClass($item) : $item;
    }

    private function resolveEventResource(mixed $event): mixed
    {
        $map = [
            ClinicHistory::class => ClinicHistoryResource::class,
            SupplyMovement::class => SupplyMovementResource::class,
            Outcome::class => OutcomeResource::class,
            ProductMovement::class => ProductMovementResource::class,
            Extraction::class => ExtractionResource::class,
            Service::class => ServiceResource::class,
            Birth::class => BirthResource::class,
        ];

        $resourceClass = $map[get_class($event)] ?? null;

        return $resourceClass ? new $resourceClass($event) : $event;
    }
}
