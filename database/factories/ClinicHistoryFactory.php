<?php

namespace Database\Factories;

use App\Models\ClinicHistory;
use App\Models\Livestock;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicHistory>
 */
class ClinicHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('CH-####'),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'attributes' => [
                'temperature' => $this->faker->randomFloat(1, 37, 41),
                'heart_rate' => $this->faker->numberBetween(40, 80),
            ],
            'livestock_id' => Livestock::factory(),
            'technician_id' => Technician::factory(),
        ];
    }
}
