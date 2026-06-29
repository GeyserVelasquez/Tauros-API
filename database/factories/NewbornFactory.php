<?php

namespace Database\Factories;

use App\Models\Birth;
use App\Models\Livestock;
use App\Models\Newborn;
use App\Models\NewbornType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Newborn>
 */
class NewbornFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'birth_id' => Birth::factory(),
            'newborn_type_id' => NewbornType::factory(),
            'livestock_id' => Livestock::factory(),
        ];
    }
}
