<?php

namespace Database\Factories;

use App\Models\Paddock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paddock>
 */
class PaddockFactory extends Factory
{
    protected $model = Paddock::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' Field',
            'code' => $this->faker->unique()->bothify('PAD-##'),
            'area' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
