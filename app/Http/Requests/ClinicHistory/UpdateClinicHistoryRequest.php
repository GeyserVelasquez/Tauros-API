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

            'diagnostics' => ['sometimes', 'required', 'array', 'min:1'],
            'diagnostics.*' => ['exists:clinic_diagnostics,id'],

            'treatments' => ['sometimes', 'required', 'array', 'min:1'],
            'treatments.*.clinical_treatment_id' => ['required', 'exists:clinical_treatments,id'],
            'treatments.*.supply_id' => ['nullable', 'exists:supplies,id'],
            'treatments.*.quantity' => ['required', 'numeric', 'min:0.01'],

            'treatments.*.is_recurring' => ['boolean'],
            'treatments.*.frequency_hours' => ['required_if:treatments.*.is_recurring,true', 'nullable', 'integer', 'min:1'],
            'treatments.*.total_doses' => ['required_if:treatments.*.is_recurring,true', 'nullable', 'integer', 'min:1'],
            'treatments.*.first_dose_date' => ['nullable', 'date'],
            'treatments.*.is_first_dose_applied' => ['boolean'],
        ];
    }
}
