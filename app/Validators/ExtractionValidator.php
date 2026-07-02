<?php

namespace App\Validators;

use App\Models\Extraction;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            'geneticable_type' => ['required', 'string'],
            'geneticable_id' => ['required', 'integer'],
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
        if (!$extraction->geneticable_type || !$extraction->geneticable_id) {
            return;
        }

        $modelClass = Relation::getMorphedModel($extraction->geneticable_type) ?? $extraction->geneticable_type;

        if (!class_exists($modelClass)) {
            throw ValidationException::withMessages([
                'geneticable_type' => ['El tipo de lote no es válido.'],
            ]);
        }

        if (!$modelClass::where('id', $extraction->geneticable_id)->exists()) {
            throw ValidationException::withMessages([
                'geneticable_id' => ['El lote seleccionado no existe.'],
            ]);
        }
    }
}
