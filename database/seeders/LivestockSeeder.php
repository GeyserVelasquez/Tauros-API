<?php

namespace Database\Seeders;

use App\Enums\AnimalCategory;
use App\Models\Breed;
use App\Models\Classification;
use App\Models\Color;
use App\Models\EntryCause;
use App\Models\Livestock;
use App\Models\Owner;
use App\Enums\State;
use App\Models\Technician;
use App\Models\Growth;
use App\Models\GrowthType;
use App\Models\Milking;
use App\Models\MilkingType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LivestockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entryCause = EntryCause::where('code', 'BORN')->first();
        $breed = Breed::where('code', 'HOLSTEIN')->first();
        $color = Color::where('code', 'WHITE')->first();
        $classification = Classification::where('code', 'GOOD')->first();
        $owner = Owner::first();
        $technician = Technician::first();

        $growthTypeGeneral = GrowthType::where('code', 'GENERAL')->first();
        $milkingTypeMechanical = MilkingType::where('code', 'MECHANICAL')->first();

        // 1. Seed 20 animals of different categories
        $categories = [
            AnimalCategory::COW,     // 12 cows
            AnimalCategory::HEIFER,  // 4 heifers
            AnimalCategory::BULL,    // 2 bulls
            AnimalCategory::STEER,   // 2 steers
        ];

        $animalNames = [
            'Lola', 'Clarabella', 'Margarita', 'Blanquita', 'Pinta', 'Estrella', 'Luna', 'Mariposa',
            'Dora', 'Bessie', 'Daisy', 'Rosita', 'Muñeca', 'Bella', 'Princesa', 'Nena',
            'Toro Sentado', 'Ferdinand', 'Hércules', 'Rambo'
        ];

        for ($i = 0; $i < 20; $i++) {
            $brandNumber = (1001 + $i) . '';
            $name = $animalNames[$i] ?? ('Animal ' . $brandNumber);
            
            // Determine category
            if ($i < 12) {
                $category = AnimalCategory::COW;
            } elseif ($i < 16) {
                $category = AnimalCategory::HEIFER;
            } elseif ($i < 18) {
                $category = AnimalCategory::BULL;
            } else {
                $category = AnimalCategory::STEER;
            }

            $birthYears = ($category === AnimalCategory::COW || $category === AnimalCategory::BULL) ? 4 : 2;

            $animal = Livestock::updateOrCreate(
                ['brand_number' => $brandNumber],
                [
                    'name' => $name,
                    'entry_date' => now()->subMonths(12),
                    'birth_date' => now()->subYears($birthYears),
                    'entry_cause_id' => $entryCause->id,
                    'state' => State::HEALTHY,
                    'animal_category' => $category,
                    'breed_id' => $breed->id,
                    'color_id' => $color->id,
                    'classification_id' => $classification->id,
                    'owner_id' => $owner->id,
                    'technician_id' => $technician->id,
                ]
            );

            // 2. Seed 15 growth records per animal (spreading over last 150 days)
            $startWeight = match ($category) {
                AnimalCategory::COW => 450,
                AnimalCategory::BULL => 500,
                AnimalCategory::HEIFER => 250,
                AnimalCategory::STEER => 300,
                default => 350,
            };

            for ($g = 0; $g < 15; $g++) {
                $daysAgo = 150 - ($g * 10);
                $date = Carbon::now()->subDays($daysAgo);
                
                // Weight increases slowly with some random factor
                $weight = $startWeight + ($g * 5) + rand(-2, 3);

                Growth::create([
                    'livestock_id' => $animal->id,
                    'made_at' => $date->toDateString(),
                    'growth_type_id' => $growthTypeGeneral->id,
                    'weight' => $weight,
                    'height' => 1.2 + ($g * 0.01) + (rand(0, 10) / 100),
                    'length' => 1.5 + ($g * 0.01) + (rand(0, 10) / 100),
                    'thoracic_width' => 0.8 + ($g * 0.01) + (rand(0, 10) / 100),
                    'technician_id' => $technician->id,
                ]);
            }

            // 3. Seed 20 milking records for females (COW, HEIFER) (spreading over last 30 days)
            if ($category === AnimalCategory::COW || $category === AnimalCategory::HEIFER) {
                for ($m = 0; $m < 20; $m++) {
                    $daysAgo = 30 - ($m * 1.5);
                    $date = Carbon::now()->subDays((int)$daysAgo);

                    // Daily production weights
                    $first = rand(60, 120) / 10;
                    $second = rand(50, 100) / 10;
                    $third = rand(20, 60) / 10;

                    Milking::create([
                        'livestock_id' => $animal->id,
                        'made_at' => $date->toDateString(),
                        'milking_type_id' => $milkingTypeMechanical->id,
                        'first_weight' => $first,
                        'second_weight' => $second,
                        'third_weight' => $third,
                        'technician_id' => $technician->id,
                    ]);
                }
            }
        }
    }
}

