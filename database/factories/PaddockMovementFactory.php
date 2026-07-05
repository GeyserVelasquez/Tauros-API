<?php

namespace Database\Factories;

use App\Models\PaddockMovement;
use App\Models\Livestock;
use App\Models\Paddock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaddockMovement>
 */
class PaddockMovementFactory extends Factory
{
    protected $model = PaddockMovement::class;

    public function definition(): array
    {
        return [
            'livestock_id' => Livestock::factory(),
            'paddock_id' => Paddock::factory(),
            'made_at' => now(),
        ];
    }
}
