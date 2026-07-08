<?php

namespace App\Services;

use App\Models\Birth;
use App\Models\Livestock;
use Illuminate\Support\Facades\DB;

class BirthRegistrationService
{
    /**
     * Registra un parto y sus crías asociadas de forma transaccional.
     *
     * @param array $data
     * @return Birth
     */
    public function register(array $data): Birth
    {
        return DB::transaction(function () use ($data) {

            $birth = Birth::create([
                'mother_id' => $data['mother_id'],
                'birth_date' => $data['birth_date'],
                'postbirth_revision_date' => $data['postbirth_revision_date'],
                'birth_type_id' => $data['birth_type_id'],
                'technician_id' => $data['technician_id'] ?? null,
            ]);

            if (!empty($data['newborns'])) {
                $this->registerNewBorns($data, $birth);
            }

            return $birth->load(['mother', 'newborns.livestock']);
        });
    }

    /**
     * @param array $data
     * @param Birth $birth
     * @return void
     */
    private function registerNewBorns(array $data, Birth $birth): void
    {
        foreach ($data['newborns'] as $calfData) {
            $calf = Livestock::create($this->parseLivestockAttributes($calfData, $data));

            $birth->newborns()->create([
                'newborn_type_id' => $calfData['newborn_type_id'],
                'livestock_id' => $calf->id,
            ]);
        }
    }

    /**
     * @param mixed $calfData
     * @param array $data
     * @return array
     */
    private function parseLivestockAttributes(mixed $calfData, array $data): array
    {
        return [
            'brand_number' => $calfData['brand_number'],
            'animal_category' => $calfData['animal_category'],
            'birth_date' => $data['birth_date'],
            'entry_date' => $data['birth_date'],
            'entry_cause_id' => $calfData['entry_cause_id'],
            'state' => $calfData['state'],
            'is_alive' => true,
            'is_enabled' => true,

            // Campos nullable contemplados
            'color_id' => $calfData['color_id'] ?? null,
            'breed_id' => $calfData['breed_id'] ?? null,
            'father_id' => $calfData['father_id'] ?? null,
            'mother_id' => $data['mother_id'],

            // Nuevos campos opcionales opcionalmente nulos
            'electronic_code' => $calfData['electronic_code'] ?? null,
            'name' => $calfData['name'] ?? null,
            'general_comment' => $calfData['general_comment'] ?? null,
        ];
    }
}
