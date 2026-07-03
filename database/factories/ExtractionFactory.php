<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\EmbrionBatch;
use App\Models\Extraction;
use App\Models\ExtractionType;
use App\Models\SemenBatch;
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
            'geneticable_type' => SemenBatch::class,
            'geneticable_id' => SemenBatch::factory(),
            'technician_id' => Technician::factory(),
            'extraction_type_id' => ExtractionType::factory(),
            'made_at' => $this->faker->date(),
        ];
    }

    public function forEmbrionBatch(): self
    {
        return $this->state(fn (array $attributes) => [
            'geneticable_type' => EmbrionBatch::class,
            'geneticable_id' => EmbrionBatch::factory(),
        ]);
    }
}
