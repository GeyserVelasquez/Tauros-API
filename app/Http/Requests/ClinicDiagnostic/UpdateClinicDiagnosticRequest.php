<?php

namespace App\Http\Requests\ClinicDiagnostic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicDiagnosticRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clinicDiagnostic = $this->route('clinic_diagnostic');

        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('clinic_diagnostics', 'code')->ignore($clinicDiagnostic)
            ],
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255'
            ],
            'attributes' => [
                'nullable',
                'array'
            ],
        ];
    }
}
