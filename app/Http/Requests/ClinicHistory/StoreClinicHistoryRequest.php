<?php

namespace App\Http\Requests\ClinicHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClinicHistoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('clinic_histories', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attributes' => ['nullable', 'array'],
            'livestock_id' => ['required', 'exists:livestock,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
        ];
    }
}
