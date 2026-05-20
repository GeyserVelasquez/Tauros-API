<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Models\Product;
use App\Models\ProductMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductMovement>
 */
class ProductMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => $this->faker->randomElement(MovementType::cases()),
            'made_at' => $this->faker->date(),
            'attributes' => [
                'notes' => $this->faker->sentence(),
                'batch_number' => $this->faker->bothify('BATCH-####'),
            ],
        ];
    }
}
