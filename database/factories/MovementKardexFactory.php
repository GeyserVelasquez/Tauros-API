<?php

namespace Database\Factories;

use App\Models\MovementKardex;
use App\Models\Supply;
use App\Models\SupplyMovement;
use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MovementKardex>
 */
class MovementKardexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_type' => Supply::class,
            'item_id' => Supply::factory(),
            'event_type' => SupplyMovement::class,
            'event_id' => SupplyMovement::factory(),
            'type' => $this->faker->randomElement(MovementType::cases()),
            'quantity' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
