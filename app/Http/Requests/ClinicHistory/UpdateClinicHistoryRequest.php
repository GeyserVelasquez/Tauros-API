<?php

namespace App\Http\Requests\ClinicHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicHistoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clinicHistory = $this->route('clinic_history');

        return [
            'code' => [
                'sometimes', 
                'required', 
                'string', 
                'max:255', 
                Rule::unique('clinic_histories', 'code')->ignore($clinicHistory)
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'attributes' => ['sometimes', 'nullable', 'array'],
            'livestock_id' => ['sometimes', 'required', 'exists:livestock,id'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
        ];
    }
}
