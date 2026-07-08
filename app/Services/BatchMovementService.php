<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Paddock;
use App\Models\Livestock;
use App\Models\PaddockMovement;
use App\Models\BatchPaddockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchMovementService
{
    public function moveToPaddock(Batch $batch, Paddock $paddock, Carbon $madeAt): void
    {
        DB::transaction(function () use ($batch, $paddock, $madeAt) {
            // Update the batch's current paddock
            $batch->update(['paddock_id' => $paddock->id]);

            // Create batch history movement
            BatchPaddockMovement::create([
                'batch_id' => $batch->id,
                'paddock_id' => $paddock->id,
                'made_at' => $madeAt,
            ]);

            // Update paddock_id for all livestock in the batch (current state)
            Livestock::where('batch_id', $batch->id)->update(['paddock_id' => $paddock->id]);

            // Retrieve all livestock IDs in this batch to insert in history (bulk insert)
            $livestockIds = Livestock::where('batch_id', $batch->id)->pluck('id');

            if ($livestockIds->isNotEmpty()) {
                $now = now();
                $records = $livestockIds->map(fn($id) => [
                    'livestock_id' => $id,
                    'paddock_id' => $paddock->id,
                    'made_at' => $madeAt,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->toArray();

                PaddockMovement::insert($records);
            }
        });
    }
}
