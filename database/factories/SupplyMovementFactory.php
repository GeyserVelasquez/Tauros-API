<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Models\Supply;
use App\Models\SupplyMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplyMovement>
 */
class SupplyMovementFactory extends Factory
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
            'type' => $this->faker->randomElement(MovementType::cases()),
            'made_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'attributes' => [
                'reason' => $this->faker->sentence(),
                'reference_number' => $this->faker->bothify('REF-####'),
            ],
        ];
    }
}
