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
use App\Models\Paddock;
use App\Models\MilkingType;
use App\Models\NewbornType;
use App\Models\DeathCause;
use App\Models\ProductType;
use App\Models\RevisionType;
use App\Models\ServiceType;
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

            ProductType::class => [
                ['code' => 'MILK', 'name' => 'Leche'],
                ['code' => 'MEAT', 'name' => 'Carne'],
                ['code' => 'SEMEN', 'name' => 'Semen'],
                ['code' => 'EMBRYO', 'name' => 'Embrión'],
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
            Paddock::class => [
                ['code' => 'MAIN', 'name' => 'Potrero Principal'],
                ['code' => 'NORTH', 'name' => 'Potrero Norte'],
                ['code' => 'SOUTH', 'name' => 'Potrero Sur'],
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

        // Seed Death Causes
        $deathCauses = [
            ['name' => 'Enfermedad / Epidemia'],
            ['name' => 'Accidente / Traumatismo'],
            ['name' => 'Vejez / Senilidad'],
            ['name' => 'Parto Distócico'],
            ['name' => 'Causa Desconocida'],
        ];

        foreach ($deathCauses as $cause) {
            DeathCause::firstOrCreate(['name' => $cause['name']], $cause);
        }
    }
}
