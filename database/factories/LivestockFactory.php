<?php

namespace Database\Factories;

use App\Enums\AnimalCategory;
use App\Models\Batch;
use App\Models\Breed;
use App\Models\Classification;
use App\Models\Color;
use App\Models\EntryCause;
use App\Models\Livestock;
use App\Models\Owner;
use App\Models\State;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<Livestock>
 */
class LivestockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $animal_category = $this->faker->randomElement(AnimalCategory::cases());
        $isFemale = $animal_category->isFemale();
        $name = $isFemale ? $this->faker->firstNameFemale() : $this->faker->firstNameMale();
        $birthDate = $this->faker->dateTimeBetween('-15 years', 'now');
        $entryDate = $this->faker->dateTimeBetween($birthDate, 'now');

        return [
            'brand_number' => (string) $this->faker->unique()->numberBetween(0, 999999),
            'electronic_code' => $this->faker->unique()->ean13(),
            'name' => $name,
            'entry_date' => $entryDate->format('Y-m-d'),
            'birth_date' => $birthDate->format('Y-m-d'),
            'general_comment' => $this->faker->sentence(),
            'tits' => $isFemale ? 4 : 0,
            'is_enabled' => true,
            'is_alive' => true,
            'entry_cause_id' => EntryCause::factory(),
            'state_id' => State::factory(),
            'animal_category' => $animal_category,
            'breed_id' => Breed::factory(),
            'color_id' => Color::factory(),
            'classification_id' => Classification::factory(),
            'owner_id' => Owner::factory(),
            'technician_id' => Technician::factory(),
            'father_id' => null,
            'mother_id' => null,
            'adoptive_mother_id' => null,
            'receiving_mother_id' => null,
        ];
    }

    public function dead(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_alive' => false,
            'is_enabled' => false,
        ]);
    }

    public function asBull(): static
    {
        return $this->state(fn(array $attributes) => [
            'animal_category' => AnimalCategory::BULL,
            'tits' => 0,
            'name' => $this->faker->firstNameMale(),
        ]);
    }

    public function asCow(): static
    {
        return $this->state(fn(array $attributes) => [
            'animal_category' => AnimalCategory::COW,
            'tits' => 4,
            'name' => $this->faker->firstNameFemale(),
        ]);
    }

    /**
     * Indicates that the animal should have a dynamically generated pedigree
     * up to the specified amount of levels.
     *
     * Caution: Database record insertion grows exponentially at a rate of
     * 2^(n+1) - 1.
     *
     * @param  int  $level
     * @return static
     */
    public function withPedigree(int $level = 1): static
    {
        if ($level <= 0) {
            return $this;
        }

        return $this->state(fn (array $attributes) => [
            'father_id' => $this->generateParent($attributes, $level)->asBull(),
            'mother_id' => $this->generateParent($attributes, $level)->asCow(),
        ]);
    }

    /**
     * Generates a parent factory with proper age constraints.
     */
    protected function generateParent(array $childAttributes, int $currentLevel): self
    {
        $childBirthdate = $childAttributes['birth_date'] ?? now();
        $parentBirthDate = $this->generateParentBirthDate($childBirthdate);

        return static::new()
            ->state([
                'birth_date' => $parentBirthDate,
                'entry_date' => $this->faker->dateTimeBetween($parentBirthDate, 'now')->format('Y-m-d'),
            ])
            ->withPedigree($currentLevel - 1);
    }

    /**
     * Generates a realistic birth_date for a parent based on the child's birth_date.
     */
    protected function generateParentBirthDate($childBirthDate): string
    {
        $childBirthDate = Carbon::parse($childBirthDate);

        return $this->faker->dateTimeBetween(
            (clone $childBirthDate)->subYears(12),
            (clone $childBirthDate)->subYears(2)
        )->format('Y-m-d');
    }
}
