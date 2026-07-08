<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Livestock;
use App\Models\LivestockCertificate;
use Illuminate\Support\Facades\DB;

class CertificateService
{
    public function assign(Certificate $certificate, array $data): void
    {
        DB::transaction(function () use ($certificate, $data) {
            $now = now();

            // Limpiar relaciones anteriores
            $certificate->batches()->detach();
            $certificate->livestock()->detach();

            // 1. Asignación por Lote (Snapshot)
            if (!empty($data['batch_id']) && $data['assign_by'] === 'batch') {
                $batchId = $data['batch_id'];
                
                $certificate->batches()->syncWithoutDetaching([$batchId]);

                $livestockIds = Livestock::where('batch_id', $batchId)->pluck('id');

                if ($livestockIds->isNotEmpty()) {
                    $records = $livestockIds->map(fn($id) => [
                        'livestock_id'   => $id,
                        'certificate_id' => $certificate->id,
                        'batch_id'       => $batchId,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ])->toArray();

                    LivestockCertificate::insert($records);
                }
            }

            // 2. Asignación Individual
            if (!empty($data['livestock_ids']) && $data['assign_by'] === 'individual') {
                $livestockIds = $data['livestock_ids'];

                $livestockData = Livestock::whereIn('id', $livestockIds)
                    ->select('id', 'batch_id')
                    ->get();

                if ($livestockData->isNotEmpty()) {
                    $records = $livestockData->map(fn($animal) => [
                        'livestock_id'   => $animal->id,
                        'certificate_id' => $certificate->id,
                        'batch_id'       => $animal->batch_id,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ])->toArray();

                    LivestockCertificate::insert($records);
                }
            }
        });
    }
}
