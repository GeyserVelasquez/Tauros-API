<?php

namespace Database\Factories;

use App\Models\Livestock;
use App\Models\SemenBatch;
use App\Models\EmbrionBatch;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'female_id' => Livestock::factory()->asCow(),
            'parentable_type' => Livestock::class,
            'parentable_id' => Livestock::factory()->asBull(),
            'technician_id' => Technician::factory(),
            'service_type_id' => ServiceType::factory(),
            'made_at' => $this->faker->date(),
        ];
    }

    public function withSemen(): self
    {
        return $this->state(fn (array $attributes) => [
            'parentable_type' => SemenBatch::class,
            'parentable_id' => SemenBatch::factory(),
        ]);
    }

    public function withEmbrion(): self
    {
        return $this->state(fn (array $attributes) => [
            'parentable_type' => EmbrionBatch::class,
            'parentable_id' => EmbrionBatch::factory(),
        ]);
    }
}
