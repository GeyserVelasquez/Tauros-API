<?php

namespace Database\Factories;

use App\Models\Abort;
use App\Models\AbortType;
use App\Models\Livestock;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Abort>
 */
class AbortFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'livestock_id' => Livestock::factory(),
            'technician_id' => Technician::factory(),
            'abort_type_id' => AbortType::factory(),
            'made_at' => $this->faker->date(),
        ];
    }
}
