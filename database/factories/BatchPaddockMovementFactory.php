<?php

namespace Database\Factories;

use App\Models\BatchPaddockMovement;
use App\Models\Batch;
use App\Models\Paddock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BatchPaddockMovement>
 */
class BatchPaddockMovementFactory extends Factory
{
    protected $model = BatchPaddockMovement::class;

    public function definition(): array
    {
        return [
            'batch_id' => Batch::factory(),
            'paddock_id' => Paddock::factory(),
            'made_at' => now(),
        ];
    }
}
