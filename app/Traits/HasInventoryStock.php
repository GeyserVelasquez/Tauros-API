<?php

namespace App\Traits;

use App\Models\MovementKardex;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

trait HasInventoryStock
{
    /**
     * Relación directa con el Kardex de movimientos.
     */
    public function movements(): MorphMany
    {
        return $this->morphMany(MovementKardex::class, 'item');
    }

    /**
     * Scope para inyectar la cantidad de stock calculada en SQL sin generar N+1.
     */
    public function scopeWithStock(Builder $query): void
    {
        $query->addSelect([
            'stock' => MovementKardex::selectRaw("
                COALESCE(
                    SUM(
                        CASE 
                            WHEN type = 'income' THEN quantity 
                            ELSE -quantity 
                        END
                    ),
                    0
                )
            ")
            ->whereColumn('item_id', $this->getTable() . '.id')
            ->where('item_type', $this->getMorphClass())
        ]);
    }
}
