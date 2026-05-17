<?php

namespace App\Http\Requests\ClinicalTreatment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicalTreatmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clinicalTreatment = $this->route('clinical_treatment');

        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('clinical_treatments', 'code')->ignore($clinicalTreatment)
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
