<?php

namespace Database\Factories;

use App\Models\Birth;
use App\Models\BirthType;
use App\Models\Livestock;
use App\Models\Technician;
use App\Models\EntryCause;
use App\Models\State;
use App\Models\NewbornType;
use App\Models\Color;
use App\Models\Breed;
use App\Enums\AnimalCategory;
use Carbon\Carbon;
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
        $motherBirthDate = (clone $birthDate)->modify('-5 years');
        $motherEntryDate = (clone $motherBirthDate)->modify('+1 year');

        return [
            'mother_id' => Livestock::factory()->asCow()->state([
                'birth_date' => $motherBirthDate->format('Y-m-d'),
                'entry_date' => $motherEntryDate->format('Y-m-d'),
            ]),
            'birth_date' => $birthDate->format('Y-m-d'),
            'postbirth_revision_date' => $revisionDate->format('Y-m-d'),
            'birth_type_id' => BirthType::factory(),
            'technician_id' => Technician::factory(),
        ];
    }

    /**
     * Define the newborns state for request payload generation.
     *
     * @param int $count
     * @return static
     */
    public function withNewBorns(int $count = 1): static
    {
        return $this->state(function (array $attributes) use ($count) {
            $newborns = [];

            $childBirthDate = $attributes['birth_date'] ?? now()->format('Y-m-d');
            $fatherBirthDate = Carbon::parse($childBirthDate)->subYears(5)->format('Y-m-d');
            $fatherEntryDate = Carbon::parse($fatherBirthDate)->addYear()->format('Y-m-d');

            for ($i = 0; $i < $count; $i++) {
                $category = $this->faker->randomElement([
                    AnimalCategory::FEMALE_CALF,
                    AnimalCategory::MALE_CALF
                ]);

                // El padre debe existir en BD para pasar la validación exists:livestock,id
                $father = Livestock::factory()->asBull()->create([
                    'birth_date' => $fatherBirthDate,
                    'entry_date' => $fatherEntryDate,
                ]);

                // El tipo de recién nacido debe existir en BD
                $newbornType = NewbornType::factory()->create();

                // Evaluamos los atributos del ganado usando make() para persistir dependencias y resolver IDs
                $livestockData = Livestock::factory()->make([
                    'animal_category' => $category,
                    'birth_date' => $childBirthDate,
                    'entry_date' => $childBirthDate,
                    'father_id' => $father->id,
                    'mother_id' => $attributes['mother_id'] ?? null,
                ])->toArray();

                $newborns[] = array_merge($livestockData, [
                    'newborn_type_id' => $newbornType->id,
                ]);
            }

            return [
                'newborns' => $newborns,
            ];
        });
    }
}
