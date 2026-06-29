<?php

namespace Database\Factories;

use App\Models\Growth;
use App\Models\GrowthType;
use App\Models\Livestock;
use App\Models\Technician;
use App\Models\Birth;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Growth>
 */
class GrowthFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'made_at' => $this->faker->date(),
            'weight' => $this->faker->randomFloat(2, 30, 800),
            'height' => $this->faker->randomFloat(2, 50, 150),
            'growthable_type' => Birth::class,
            'growthable_id' => Birth::factory(),
            'growth_type_id' => GrowthType::factory(),
            'livestock_id' => Livestock::factory(),
            'technician_id' => Technician::factory(),
        ];
    }
}
