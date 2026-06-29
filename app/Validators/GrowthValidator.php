<?php

namespace App\Validators;

use App\Models\Growth;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;

class GrowthValidator extends Validator
{
    /**
     * Valida la integridad de negocio de un modelo Growth.
     *
     * @throws ValidationException
     */
    public function validate(Growth $growth): void
    {
        $data = $growth->toArray();

        $rules = [
            'weight' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
            'livestock_id' => ['required', 'exists:livestock,id'],
            'growth_type_id' => ['required', 'exists:growth_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'growthable_type' => ['nullable', 'string'],
            'growthable_id' => ['nullable', 'integer'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->validateGrowthableExists($growth);
    }

    private function validateGrowthableExists(Growth $growth): void
    {
        if (!$growth->growthable_type || !$growth->growthable_id) {
            return;
        }

        if (!class_exists($growth->growthable_type)) {
            throw ValidationException::withMessages([
                'growthable_type' => ['El tipo de origen del pesaje no es válido.'],
            ]);
        }

        $modelClass = $growth->growthable_type;
        if (!$modelClass::where('id', $growth->growthable_id)->exists()) {
            throw ValidationException::withMessages([
                'growthable_id' => ['El origen seleccionado no existe.'],
            ]);
        }
    }
}
