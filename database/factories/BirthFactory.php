<?php

namespace Database\Factories;

use App\Models\Birth;
use App\Models\BirthType;
use App\Models\Livestock;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Birth>
 */
class BirthFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $birthDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $revisionDate = (clone $birthDate)->modify('+3 days');

        return [
            'mother_id' => Livestock::factory()->asCow(),
            'birth_date' => $birthDate->format('Y-m-d'),
            'postbirth_revision_date' => $revisionDate->format('Y-m-d'),
            'birth_type_id' => BirthType::factory(),
            'technician_id' => Technician::factory(),
        ];
    }
}
