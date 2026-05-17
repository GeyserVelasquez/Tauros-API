<?php

namespace Database\Factories;

use App\Models\Livestock;
use App\Models\Milking;
use App\Models\MilkingType;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Milking>
 */
class MilkingFactory extends Factory
{
    protected $model = Milking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'livestock_id' => Livestock::factory(),
            'milking_type_id' => MilkingType::factory(),
            'technician_id' => Technician::factory(),
            'made_at' => $this->faker->date(),
            'first_weight' => $this->faker->randomFloat(2, 5, 20),
            'second_weight' => $this->faker->randomFloat(2, 5, 20),
            'third_weight' => $this->faker->randomFloat(2, 5, 20),
        ];
    }
}
