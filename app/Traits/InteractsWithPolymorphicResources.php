<?php

namespace App\Traits;

use App\Models\Batch;
use App\Models\EmbrionBatch;
use App\Http\Resources\BatchResource;
use App\Http\Resources\EmbrionBatchResource;
use Illuminate\Http\Resources\Json\JsonResource;

trait InteractsWithPolymorphicResources
{
    /**
     * Mapeo de modelos a sus respectivos Resources.
     */
    protected static array $polymorphicMap = [
        Batch::class => BatchResource::class,
        EmbrionBatch::class => EmbrionBatchResource::class,
        // Agrega aquí otros mapeos polimórficos según sea necesario
    ];

    /**
     * Resuelve el Resource adecuado para un modelo dado.
     */
    protected function resolvePolymorphicResource(mixed $model): mixed
    {
        if (!$model) {
            return null;
        }

        $modelClass = get_class($model);

        if (isset(static::$polymorphicMap[$modelClass])) {
            $resourceClass = static::$polymorphicMap[$modelClass];
            return new $resourceClass($model);
        }

        return $model;
    }
}
