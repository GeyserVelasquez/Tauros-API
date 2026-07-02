<?php

namespace App\Http\Resources;

use App\Models\Batch;
use App\Models\EmbrionBatch;
use App\Models\SemenBatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtractionResource extends JsonResource
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
            'geneticable_type' => $this->geneticable_type,
            'geneticable_id' => $this->geneticable_id,
            'technician_id' => $this->technician_id,
            'extraction_type_id' => $this->extraction_type_id,
            'made_at' => $this->made_at?->format('Y-m-d'),
            'quantity' => $this->quantity,

            'geneticable' => $this->whenLoaded('geneticable', fn () => $this->resolveBatchResource($this->geneticable)),

            'technician' => new TechnicianResource($this->whenLoaded('technician')),
            'extraction_type' => new ExtractionTypeResource($this->whenLoaded('extractionType')),
        ];
    }

    /**
     * Resuelve el Resource adecuado basándose en el tipo de modelo instanciado.
     */
    private function resolveBatchResource(mixed $batch): mixed
    {
        if (is_null($batch)) {
            return null;
        }

        $map = [
            Batch::class => BatchResource::class,
            EmbrionBatch::class => EmbrionBatchResource::class,
            SemenBatch::class => SemenBatchResource::class,
        ];

        $resourceClass = $map[get_class($batch)] ?? null;

        return $resourceClass ? new $resourceClass($batch) : $batch;
    }
}
