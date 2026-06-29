<?php

namespace App\Traits;

use App\Models\Growth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasGrowth
{
    public function growth(): MorphOne
    {
        return $this->morphOne(Growth::class, 'growthable');
    }

    /**
     * Registra un modelo y opcionalmente su registro de crecimiento asociado.
     */
    public static function register(array $payload): static
    {
        $growthData = Arr::pull($payload, 'growth');

        return DB::transaction(fn () => static::executeRegistration($payload, $growthData));
    }

    /**
     * Actualiza un modelo y su registro de crecimiento asociado.
     */
    public function amend(array $payload): bool
    {
        $growthData = Arr::pull($payload, 'growth');

        return DB::transaction(fn () => $this->executeAmendment($payload, $growthData));
    }

    private static function executeRegistration(array $payload, ?array $growthData): static
    {
        $model = static::create($payload);
        
        if ($growthData) {
            $model->syncGrowth($growthData);
        }

        return $model;
    }

    private function executeAmendment(array $payload, ?array $growthData): bool
    {
        $updated = $this->update($payload);
        
        if ($growthData) {
            $this->syncGrowth($growthData);
        }

        return $updated;
    }

    /**
     * Sincroniza el registro de crecimiento (pesaje/talla).
     */
    public function syncGrowth(?array $growthData): void
    {
        if (empty($growthData)) {
            $this->growth()->delete();
            return;
        }

        $this->growth()->updateOrCreate(
            ['livestock_id' => $this->livestock_id ?? $growthData['livestock_id'] ?? null],
            [
                'weight' => $growthData['weight'],
                'height' => $growthData['height'],
                'made_at' => $growthData['made_at'] ?? now(),
                'growth_type_id' => $growthData['growth_type_id'],
                'technician_id' => $growthData['technician_id'] ?? $this->technician_id ?? null,
            ]
        );
    }
}
