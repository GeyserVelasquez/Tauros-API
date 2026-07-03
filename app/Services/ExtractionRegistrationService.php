<?php

namespace App\Services;

use App\Models\Extraction;
use App\Models\MovementKardex;
use App\Enums\MovementType;
use App\Models\SemenBatch;
use App\Models\EmbrionBatch;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            // Normalizar FQCN a alias de morph map si es necesario
            $morphMap = Relation::morphMap();
            $alias = array_search($data['geneticable_type'], $morphMap);
            $data['geneticable_type'] = $alias !== false ? $alias : $data['geneticable_type'];

            // Si no se proporcionó geneticable_id, creamos el lote dinámicamente
            if (empty($data['geneticable_id'])) {
                $batch = $this->createBatch($data);
                $data['geneticable_id'] = $batch->id;
                $data['geneticable_type'] = $batch->getMorphClass();
            }

            // 1. Crear el registro de extracción
            $extraction = Extraction::create([
                'geneticable_type' => $data['geneticable_type'],
                'geneticable_id' => $data['geneticable_id'],
                'made_at' => $data['made_at'],
                'technician_id' => $data['technician_id'] ?? null,
                'extraction_type_id' => $data['extraction_type_id'],
            ]);

            // 2. Registrar en el Kardex solo si es un tipo de lote inventariable (Semen o Embrión)
            if (in_array($extraction->geneticable_type, $this->inventarizableTypes)) {
                $movement = $extraction->movements()->create([
                    'item_type' => $extraction->geneticable_type,
                    'item_id' => $extraction->geneticable_id,
                    'type' => MovementType::INCOME,
                    'quantity' => $data['quantity'], // Unidades de dosis/embriones
                    'date' => $extraction->made_at,
                ]);

                $extraction->load(['geneticable', 'technician', 'extractionType']);
                $extraction->setAttribute('quantity', $movement->quantity);
            } else {
                $extraction->load(['geneticable', 'technician', 'extractionType']);
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
            if (isset($data['geneticable_type'])) {
                $morphMap = Relation::morphMap();
                $alias = array_search($data['geneticable_type'], $morphMap);
                $data['geneticable_type'] = $alias !== false ? $alias : $data['geneticable_type'];
            }

            $extraction->update($data);

            if (in_array($extraction->geneticable_type, $this->inventarizableTypes)) {
                if (isset($data['quantity'])) {
                    $movement = $extraction->movements()->updateOrCreate(
                        [],
                        [
                            'item_type' => $extraction->geneticable_type,
                            'item_id' => $extraction->geneticable_id,
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

            return $extraction->load(['geneticable', 'technician', 'extractionType']);
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
        $batchType = $data['geneticable_type'];

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
