<?php

namespace Database\Factories;

use App\Models\ClinicDiagnostic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicDiagnostic>
 */
class ClinicDiagnosticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('DIAG-####'),
            'name' => $this->faker->sentence(3),
            'attributes' => [
                'severity' => $this->faker->randomElement(['low', 'medium', 'high']),
                'category' => $this->faker->word(),
            ],
        ];
    }
}
