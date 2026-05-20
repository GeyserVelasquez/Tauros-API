<?php

namespace Database\Factories;

use App\Enums\RevisionResult;
use App\Models\Livestock;
use App\Models\Revision;
use App\Models\RevisionType;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Revision>
 */
class RevisionFactory extends Factory
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
            'made_at' => $this->faker->date(),
            'revision_result' => $this->faker->randomElement(RevisionResult::cases()),
            'revision_type_id' => RevisionType::factory(),
            'technician_id' => Technician::factory(),
        ];
    }
}
