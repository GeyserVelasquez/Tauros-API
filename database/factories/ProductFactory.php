<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('PRO-####'),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'attributes' => [
                'unit' => $this->faker->randomElement(['kg', 'ton', 'unit']),
                'origin' => $this->faker->country(),
            ],
            'product_type_id' => ProductType::factory(),
        ];
    }
}
