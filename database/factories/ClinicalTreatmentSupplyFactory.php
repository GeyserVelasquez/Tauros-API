<?php

namespace Database\Factories;

use App\Models\ClinicalTreatment;
use App\Models\ClinicalTreatmentSupply;
use App\Models\Supply;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicalTreatmentSupply>
 */
class ClinicalTreatmentSupplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supply_id' => Supply::factory(),
            'quantity' => $this->faker->randomFloat(2, 0, 100),
            'clinical_treatment_id' => ClinicalTreatment::factory(),
        ];
    }
}
