<?php

namespace App\Validators;

use App\Models\Newborn;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;

class NewbornValidator extends Validator
{
    /**
     * Valida la integridad de negocio de un modelo Newborn.
     *
     * @throws ValidationException
     */
    public function validate(Newborn $newborn): void
    {
        $data = $newborn->toArray();

        $rules = [
            'birth_id' => ['required', 'exists:births,id'],
            'newborn_type_id' => ['required', 'exists:newborn_types,id'],
            'livestock_id' => ['required', 'exists:livestock,id'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
