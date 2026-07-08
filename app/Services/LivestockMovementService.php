<?php

namespace App\Services;

use App\Models\Livestock;
use App\Models\Paddock;
use App\Models\Batch;
use App\Models\PaddockMovement;
use App\Models\BatchMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LivestockMovementService
{
    public function moveToPaddock(Livestock $livestock, Paddock $paddock, Carbon $madeAt): void
    {
        DB::transaction(function () use ($livestock, $paddock, $madeAt) {
            $livestock->update(['paddock_id' => $paddock->id]);

            PaddockMovement::create([
                'livestock_id' => $livestock->id,
                'paddock_id' => $paddock->id,
                'made_at' => $madeAt,
            ]);
        });
    }

    public function moveToBatch(Livestock $livestock, Batch $batch, Carbon $madeAt): void
    {
        DB::transaction(function () use ($livestock, $batch, $madeAt) {
            $livestock->update(['batch_id' => $batch->id]);

            BatchMovement::create([
                'livestock_id' => $livestock->id,
                'batch_id' => $batch->id,
                'made_at' => $madeAt,
                'attributes' => [],
            ]);
        });
    }
}
