<?php

namespace App\Validators;

use App\Models\Extraction;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;

class ExtractionValidator extends Validator
{
    /**
     * Valida la integridad de negocio de un modelo Extraction.
     *
     * @throws ValidationException
     */
    public function validate(Extraction $extraction): void
    {
        $data = $extraction->toArray();

        $rules = [
            'batch_type' => ['required', 'string'],
            'batch_id' => ['required', 'integer'],
            'extraction_type_id' => ['required', 'exists:extraction_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->validateMorphRelationship($extraction);
    }

    private function validateMorphRelationship(Extraction $extraction): void
    {
        if (!$extraction->batch_type || !$extraction->batch_id) {
            return;
        }

        if (!class_exists($extraction->batch_type)) {
            throw ValidationException::withMessages([
                'batch_type' => ['El tipo de lote no es válido.'],
            ]);
        }

        $model = new $extraction->batch_type;
        if (!$model->where('id', $extraction->batch_id)->exists()) {
            throw ValidationException::withMessages([
                'batch_id' => ['El lote seleccionado no existe.'],
            ]);
        }
    }
}
