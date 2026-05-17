<?php

namespace Database\Factories;

use App\Models\ClinicalTreatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicalTreatment>
 */
class ClinicalTreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('TREAT-####'),
            'name' => $this->faker->sentence(3),
            'attributes' => [
                'duration' => $this->faker->randomElement(['3 days', '5 days', '7 days']),
                'dosage' => $this->faker->word(),
            ],
        ];
    }
}
