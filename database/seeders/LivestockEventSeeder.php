<?php

namespace Database\Seeders;

use App\Enums\RevisionResult;
use App\Models\Batch;
use App\Models\BatchMovement;
use App\Models\Livestock;
use App\Models\Revision;
use App\Models\RevisionType;
use App\Models\Teasing;
use App\Models\Technique;
use Illuminate\Database\Seeder;

class LivestockEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livestock = Livestock::first();
        $batch = Batch::first();
        $technique = Technique::first();
        $revisionType = RevisionType::where('code', 'GENERAL')->first();

        BatchMovement::create([
            'batch_id' => $batch->id,
            'livestock_id' => $livestock->id,
            'made_at' => now(),
            'attributes' => ['reason' => 'Agrupación por edad'],
        ]);

        Revision::create([
            'livestock_id' => $livestock->id,
            'made_at' => now(),
            'revision_result' => RevisionResult::PREGNANT,
            'revision_type_id' => $revisionType->id,
            'technique_id' => $technique->id,
        ]);

        Teasing::create([
            'livestock_id' => $livestock->id,
            'technique_id' => $technique->id,
            'detected_at' => now(),
        ]);
    }
}
