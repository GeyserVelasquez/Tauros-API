<?php

namespace App\Validators;

use App\Models\ClinicHistory;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\ValidationException;

class ClinicHistoryValidator extends Validator
{
    /**
     * Valida la integridad de negocio de un modelo ClinicHistory.
     *
     * @throws ValidationException
     */
    public function validate(ClinicHistory $clinicHistory): void
    {
        $data = $clinicHistory->toArray();

        $rules = [
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'livestock_id' => ['required', 'exists:livestock,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'attributes' => ['nullable', 'array'],
        ];

        $validator = FacadeValidator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
