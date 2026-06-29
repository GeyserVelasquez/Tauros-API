<?php

namespace Database\Factories;

use App\Models\Livestock;
use App\Models\SemenBatch;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SemenBatch>
 */
class SemenBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('SB-####'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'livestock_id' => Livestock::factory(),
            'technician_id' => Technician::factory(),
        ];
    }

    public function withoutLivestock(): static
    {
        return $this->state(fn (array $attributes) => [
            'livestock_id' => null,
        ]);
    }
}
