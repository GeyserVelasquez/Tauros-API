<?php

namespace App\Http\Requests\ClinicalTreatment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClinicalTreatmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clinical_treatments', 'code')
            ],
            'name' => ['required', 'string', 'max:255'],
            'attributes' => ['nullable', 'array'],
        ];
    }
}
