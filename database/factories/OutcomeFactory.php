<?php

namespace Database\Factories;

use App\Models\Outcome;
use App\Models\OutcomeType;
use App\Models\Livestock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Outcome>
 */
class OutcomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'livestock_id' => Livestock::factory(),
            'outcome_type_id' => OutcomeType::factory(),
            'made_at' => $this->faker->date(),
        ];
    }
}
