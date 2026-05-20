<?php

namespace App\Http\Requests\Teasing;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeasingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'livestock_id' => ['required', 'exists:livestock,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'detected_at' => ['required', 'date'],
        ];
    }
}
