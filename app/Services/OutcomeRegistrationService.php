<?php

namespace App\Services;

use App\Models\Outcome;
use App\Models\Livestock;
use Illuminate\Support\Facades\DB;

class OutcomeRegistrationService
{
    /**
     * Registra una salida de animal y desactiva el animal.
     */
    public function register(array $data): Outcome
    {
        return DB::transaction(function () use ($data) {
            // 1. Desactivar el animal
            $livestock = Livestock::findOrFail($data['livestock_id']);
            $livestock->is_enabled = false;
            if ($data['outcome_type'] === 'death') {
                $livestock->is_alive = false;
            }
            $livestock->save();

            // 2. Crear el registro de salida
            $outcome = Outcome::create($data);

            return $outcome->load(['livestock', 'deathCause']);
        });
    }

    /**
     * Actualiza un registro de salida y sincroniza el estado del animal.
     */
    public function update(Outcome $outcome, array $data): Outcome
    {
        return DB::transaction(function () use ($outcome, $data) {
            $oldLivestockId = $outcome->livestock_id;

            // Revertir el animal antiguo si cambió de animal
            if ($oldLivestockId !== $data['livestock_id']) {
                $oldLivestock = Livestock::find($oldLivestockId);
                if ($oldLivestock) {
                    $oldLivestock->is_enabled = true;
                    $oldLivestock->is_alive = true;
                    $oldLivestock->save();
                }
            }

            // Actualizar la salida
            $outcome->update($data);

            // Actualizar el estado del animal actual
            $livestock = $outcome->livestock;
            if ($livestock) {
                $livestock->is_enabled = false;
                if ($outcome->outcome_type === 'death') {
                    $livestock->is_alive = false;
                } else {
                    $livestock->is_alive = true;
                }
                $livestock->save();
            }

            return $outcome->load(['livestock', 'deathCause']);
        });
    }

    /**
     * Elimina el registro de salida y reactiva al animal.
     */
    public function delete(Outcome $outcome): void
    {
        DB::transaction(function () use ($outcome) {
            // 1. Reactivar al animal
            $livestock = $outcome->livestock;
            if ($livestock) {
                $livestock->is_enabled = true;
                $livestock->is_alive = true;
                $livestock->save();
            }

            // 2. Eliminar la salida
            $outcome->delete();
        });
    }
}
