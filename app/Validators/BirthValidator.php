<?php

namespace App\Validators;

use App\Models\Birth;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;

class BirthValidator extends Validator
{
    /**
     * Valida la integridad de negocio de un modelo Birth.
     *
     * @throws ValidationException
     */
    public function validate(Birth $birth): void
    {
        $data = $birth->toArray();

        $rules = [
            'mother_id' => ['required', 'exists:livestock,id'],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'postbirth_revision_date' => ['required', 'date', 'after_or_equal:birth_date'],
            'birth_type_id' => ['required', 'exists:birth_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->validateBiologicalRules($birth);
    }

    private function validateBiologicalRules(Birth $birth): void
    {
        $this->validateMotherGender($birth);
    }

    private function validateMotherGender(Birth $birth): void
    {
        $mother = $birth->mother;

        if ($mother && !$mother->animal_category->isFemale()) {
            throw ValidationException::withMessages([
                'mother_id' => ['El registro de parto solo puede asociarse a una madre de categoría femenina.'],
            ]);
        }
    }
}
