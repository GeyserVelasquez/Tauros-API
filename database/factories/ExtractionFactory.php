<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Extraction;
use App\Models\ExtractionType;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Extraction>
 */
class ExtractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_type' => Batch::class,
            'batch_id' => Batch::factory(),
            'technician_id' => Technician::factory(),
            'extraction_type_id' => ExtractionType::factory(),
            'made_at' => $this->faker->date(),
        ];
    }

    public function forEmbrionBatch(): self
    {
        return $this->state(fn (array $attributes) => [
            'batch_type' => \App\Models\EmbrionBatch::class,
            'batch_id' => \App\Models\EmbrionBatch::factory(),
        ]);
    }
}
