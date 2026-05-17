<?php

namespace Database\Seeders;

use App\Models\AbortType;
use App\Models\BirthType;
use App\Models\Breed;
use App\Models\Classification;
use App\Models\Color;
use App\Models\EmbrionExtractionType;
use App\Models\EntryCause;
use App\Models\ExtractionType;
use App\Models\GrowthType;
use App\Models\Herd;
use App\Models\MilkingType;
use App\Models\NewbornType;
use App\Models\OutcomeType;
use App\Models\ProductMovementType;
use App\Models\ProductType;
use App\Models\Result;
use App\Models\RevisionType;
use App\Models\ServiceType;
use App\Models\State;
use App\Models\SupplyType;
use App\Models\Technician;
use Illuminate\Database\Seeder;

class LookUpTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            EntryCause::class => [
                ['code' => 'BORN', 'name' => 'Nacimiento'],
                ['code' => 'PURCHASE', 'name' => 'Compra'],
                ['code' => 'GIFT', 'name' => 'Regalo'],
                ['code' => 'TRANSFER', 'name' => 'Traspaso'],
            ],
            State::class => [
                ['code' => 'HEALTHY', 'name' => 'Sano'],
                ['code' => 'SICK', 'name' => 'Enfermo'],
                ['code' => 'TREATMENT', 'name' => 'En Tratamiento'],
                ['code' => 'QUARANTINE', 'name' => 'En Cuarentena'],
            ],
            Classification::class => [
                ['code' => 'GOOD', 'name' => 'Bueno'],
                ['code' => 'REGULAR', 'name' => 'Regular'],
                ['code' => 'BAD', 'name' => 'Malo'],
            ],
            Color::class => [
                ['code' => 'WHITE', 'name' => 'Blanco'],
                ['code' => 'BLACK', 'name' => 'Negro'],
                ['code' => 'BROWN', 'name' => 'Castaño'],
                ['code' => 'SPOTTED', 'name' => 'Manchado'],
                ['code' => 'GREY', 'name' => 'Gris'],
            ],
            Breed::class => [
                ['code' => 'HOLSTEIN', 'name' => 'Holstein'],
                ['code' => 'JERSEY', 'name' => 'Jersey'],
                ['code' => 'ANGUS', 'name' => 'Angus'],
                ['code' => 'BRAHMAN', 'name' => 'Brahman'],
                ['code' => 'ZEBU', 'name' => 'Cebú'],
            ],
            OutcomeType::class => [
                ['code' => 'SALE', 'name' => 'Venta'],
                ['code' => 'DEATH', 'name' => 'Muerte'],
                ['code' => 'SLAUGHTER', 'name' => 'Sacrificio'],
                ['code' => 'TRANSFER', 'name' => 'Traspaso'],
                ['code' => 'THEFT', 'name' => 'Robo'],
            ],
            ProductType::class => [
                ['code' => 'MILK', 'name' => 'Leche'],
                ['code' => 'MEAT', 'name' => 'Carne'],
                ['code' => 'SEMEN', 'name' => 'Semen'],
                ['code' => 'EMBRYO', 'name' => 'Embrión'],
            ],
            ProductMovementType::class => [
                ['code' => 'IN', 'name' => 'Entrada'],
                ['code' => 'OUT', 'name' => 'Salida'],
                ['code' => 'ADJUST', 'name' => 'Ajuste'],
            ],
            SupplyType::class => [
                ['code' => 'MEDICINE', 'name' => 'Medicamento'],
                ['code' => 'FEED', 'name' => 'Alimento'],
                ['code' => 'TOOL', 'name' => 'Herramienta'],
                ['code' => 'VACCINE', 'name' => 'Vacuna'],
            ],
            RevisionType::class => [
                ['code' => 'GENERAL', 'name' => 'General'],
                ['code' => 'REPRODUCTIVE', 'name' => 'Reproductiva'],
                ['code' => 'CLINICAL', 'name' => 'Clínica'],
                ['code' => 'POST-MORTEM', 'name' => 'Post-mortem'],
            ],
            MilkingType::class => [
                ['code' => 'MANUAL', 'name' => 'Manual'],
                ['code' => 'MECHANICAL', 'name' => 'Mecánica'],
            ],
            AbortType::class => [
                ['code' => 'SPONTANEOUS', 'name' => 'Espontáneo'],
                ['code' => 'INDUCED', 'name' => 'Inducido'],
                ['code' => 'ACCIDENTAL', 'name' => 'Accidental'],
            ],
            GrowthType::class => [
                ['code' => 'BIRTH', 'name' => 'Al Nacer'],
                ['code' => 'GENERAL', 'name' => 'General'],
                ['code' => 'POSTBIRTH', 'name' => 'Post Parto'],
            ],
            ServiceType::class => [
                ['code' => 'NATURAL', 'name' => 'Monta Natural'],
                ['code' => 'AI', 'name' => 'Inseminación Artificial'],
                ['code' => 'TE', 'name' => 'Transferencia de Embriones'],
            ],
            BirthType::class => [
                ['code' => 'SINGLE', 'name' => 'Simple'],
                ['code' => 'MULTIPLE', 'name' => 'Múltiple'],
                ['code' => 'DYSTOCIC', 'name' => 'Distócico'],
            ],
            NewbornType::class => [
                ['code' => 'ALIVE', 'name' => 'Vivo'],
                ['code' => 'STILLBORN', 'name' => 'Muerto al nacer'],
                ['code' => 'WEAK', 'name' => 'Débil'],
            ],
            ExtractionType::class => [
                ['code' => 'ASPIR', 'name' => 'Aspiracion'],
                ['code' => 'RECOL', 'name' => 'Recolleción'],
                ['code' => 'SURGICAL', 'name' => 'Quirúrgica'],
                ['code' => 'NON-SURGICAL', 'name' => 'No Quirúrgica'],
            ],
            Herd::class => [
                ['code' => 'MAIN', 'name' => 'Hato Principal'],
                ['code' => 'NORTH', 'name' => 'Hato Norte'],
                ['code' => 'SOUTH', 'name' => 'Hato Sur'],
            ],
            Technician::class => [
                ['code' => 'V-14789456', 'name' => 'Técnico Principal', 'telephone' => '+584123456789'],
                ['code' => 'V-28124536', 'name' => 'Veterinario Senior', 'telephone' => '+584227558955'],
            ],
        ];

        foreach ($data as $model => $rows) {
            foreach ($rows as $row) {
                $model::updateOrCreate(['code' => $row['code']], $row);
            }
        }
    }
}
