<?php

namespace Database\Factories;

use App\Models\EmbrionBatch;
use App\Models\Livestock;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmbrionBatch>
 */
class EmbrionBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('EB-####'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'mother_id' => Livestock::factory(),
            'father_id' => Livestock::factory(),
            'technician_id' => Technician::factory(),
        ];
    }

    public function withoutLivestockParents(): static
    {
        return $this->state(fn (array $attributes) => [
            'mother_id' => null,
            'father_id' => null,
        ]);
    }
}
