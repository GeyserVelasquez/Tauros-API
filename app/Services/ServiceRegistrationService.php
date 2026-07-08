<?php

namespace App\Services;

use App\Models\Service;
use App\Models\MovementKardex;
use App\Enums\MovementType;
use App\Models\Livestock;
use Illuminate\Support\Facades\DB;

class ServiceRegistrationService
{
    /**
     * Registra un servicio y su movimiento de Kardex asociado (si aplica).
     *
     * @param array $data
     * @return Service
     */
    public function register(array $data): Service
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear el registro de servicio
            $service = Service::create([
                'female_id' => $data['female_id'],
                'parentable_type' => $data['parentable_type'],
                'parentable_id' => $data['parentable_id'],
                'technician_id' => $data['technician_id'] ?? null,
                'service_type_id' => $data['service_type_id'],
                'made_at' => $data['made_at'],
            ]);

            // 2. Registrar el consumo de inventario en el Kardex
            // Solo si el parentable NO es un animal en vivo (es decir, es pajuela de semen o embrión)
            if ($service->parentable_type !== Livestock::class) {
                MovementKardex::create([
                    'item_type' => $service->parentable_type,
                    'item_id' => $service->parentable_id,
                    'type' => MovementType::OUTCOME,
                    'quantity' => $data['quantity'] ?? 1, // Consume 1 dosis/embrión por defecto
                    'event_type' => $service->getMorphClass(),
                    'event_id' => $service->id,
                    'date' => $service->made_at,
                ]);
            }

            return $service->load(['female', 'parentable', 'technician', 'serviceType']);
        });
    }

    /**
     * Actualiza un servicio y sincroniza su Kardex.
     *
     * @param Service $service
     * @param array $data
     * @return Service
     */
    public function update(Service $service, array $data): Service
    {
        return DB::transaction(function () use ($service, $data) {
            $service->update($data);

            // Sincronizar Kardex si el parentable no es un animal en vivo
            if ($service->parentable_type !== Livestock::class) {
                $service->movements()->updateOrCreate(
                    [
                        'event_type' => $service->getMorphClass(),
                        'event_id' => $service->id,
                    ],
                    [
                        'item_type' => $service->parentable_type,
                        'item_id' => $service->parentable_id,
                        'type' => MovementType::OUTCOME,
                        'quantity' => $data['quantity'] ?? 1,
                        'date' => $service->made_at,
                    ]
                );
            } else {
                // Si cambió a un animal en vivo (inseminación natural), eliminar el movimiento anterior si existía
                $service->movements()->delete();
            }

            return $service->load(['female', 'parentable', 'technician', 'serviceType']);
        });
    }
}
