<?php

namespace App\Http\Requests\Birth;

use Illuminate\Foundation\Http\FormRequest;

class StoreBirthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mother_id' => ['required', 'exists:livestock,id'],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'postbirth_revision_date' => ['required', 'date', 'after_or_equal:birth_date'],
            'birth_type_id' => ['required', 'exists:birth_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
        ];
    }
}
