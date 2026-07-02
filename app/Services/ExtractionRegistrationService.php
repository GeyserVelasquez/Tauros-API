<?php

namespace App\Services;

use App\Models\Extraction;
use App\Models\MovementKardex;
use App\Enums\MovementType;
use App\Models\SemenBatch;
use App\Models\EmbrionBatch;
use Illuminate\Support\Facades\DB;

class ExtractionRegistrationService
{
    /**
     * Tipos de lote que se consideran inventariables en el Kardex.
     */
    protected array $inventarizableTypes = [
        SemenBatch::class,
        EmbrionBatch::class,
        'semen_batch',
        'embrion_batch',
    ];

    /**
     * Registra una extracción y su movimiento de Kardex asociado (si aplica).
     *
     * @param array $data
     * @return Extraction
     */
    public function register(array $data): Extraction
    {
        return DB::transaction(function () use ($data) {
            // Si no se proporcionó batch_id, creamos el lote dinámicamente
            if (empty($data['batch_id'])) {
                $batch = $this->createBatch($data);
                $data['batch_id'] = $batch->id;
                $data['batch_type'] = get_class($batch);
            }

            // 1. Crear el registro de extracción
            $extraction = Extraction::create([
                'batch_type' => $data['batch_type'],
                'batch_id' => $data['batch_id'],
                'made_at' => $data['made_at'],
                'technician_id' => $data['technician_id'] ?? null,
                'extraction_type_id' => $data['extraction_type_id'],
            ]);

            // 2. Registrar en el Kardex solo si es un tipo de lote inventariable (Semen o Embrión)
            if (in_array($extraction->batch_type, $this->inventarizableTypes)) {
                $movement = MovementKardex::create([
                    'item_type' => $extraction->batch_type,
                    'item_id' => $extraction->batch_id,
                    'type' => MovementType::INCOME,
                    'quantity' => $data['quantity'], // Unidades de dosis/embriones
                    'event_type' => Extraction::class,
                    'event_id' => $extraction->id,
                    'date' => $extraction->made_at,
                ]);

                $extraction->load(['batch', 'technician', 'extractionType']);
                $extraction->setAttribute('quantity', $movement->quantity);
            } else {
                $extraction->load(['batch', 'technician', 'extractionType']);
                $extraction->setAttribute('quantity', 0);
            }

            return $extraction;
        });
    }

    /**
     * Actualiza una extracción y sincroniza su Kardex (si aplica).
     *
     * @param Extraction $extraction
     * @param array $data
     * @return Extraction
     */
    public function update(Extraction $extraction, array $data): Extraction
    {
        return DB::transaction(function () use ($extraction, $data) {
            $extraction->update($data);

            if (in_array($extraction->batch_type, $this->inventarizableTypes)) {
                if (isset($data['quantity'])) {
                    $movement = $extraction->movements()->updateOrCreate(
                        [
                            'event_type' => Extraction::class,
                            'event_id' => $extraction->id,
                        ],
                        [
                            'item_type' => $extraction->batch_type,
                            'item_id' => $extraction->batch_id,
                            'type' => MovementType::INCOME,
                            'quantity' => $data['quantity'],
                            'date' => $extraction->made_at,
                        ]
                    );
                    $extraction->setAttribute('quantity', $movement->quantity);
                } else {
                    $extraction->setAttribute('quantity', $extraction->movements()->sum('quantity'));
                }
            } else {
                // Si cambió a un tipo no inventarizable, eliminar el movimiento del Kardex si existía
                $extraction->movements()->delete();
                $extraction->setAttribute('quantity', 0);
            }

            return $extraction->load(['batch', 'technician', 'extractionType']);
        });
    }

    /**
     * Crea un lote (Semen o Embrión) dinámicamente a partir de los datos.
     *
     * @param array $data
     * @return mixed
     */
    protected function createBatch(array $data)
    {
        $batchType = $data['batch_type'];

        if ($batchType === SemenBatch::class || $batchType === 'semen_batch') {
            return SemenBatch::create([
                'code' => $data['code'],
                'name' => $data['name'] ?? ('Lote Semen ' . $data['code']),
                'livestock_id' => $data['livestock_id'] ?? $data['female_id'], // Donante de semen (toro)
                'technician_id' => $data['technician_id'] ?? null,
            ]);
        }

        if ($batchType === EmbrionBatch::class || $batchType === 'embrion_batch') {
            return EmbrionBatch::create([
                'code' => $data['code'],
                'name' => $data['name'] ?? ('Lote Embrión ' . $data['code']),
                'mother_id' => $data['female_id'], // Donante de óvulo (vaca)
                'father_id' => $data['male_id'] ?? null, // Toro padre (opcional)
                'technician_id' => $data['technician_id'] ?? null,
            ]);
        }

        throw new \InvalidArgumentException("No es posible crear dinámicamente un lote del tipo: {$batchType}");
    }
}
