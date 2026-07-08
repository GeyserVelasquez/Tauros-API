<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Paddock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('BAT-??-####'),
            'name' => $this->faker->word(),
            'paddock_id' => Paddock::factory(),
        ];
    }
}
