<?php

namespace App\Http\Requests\Abort;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbortRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'livestock_id' => ['sometimes', 'required', 'exists:livestock,id'],
            'made_at' => ['sometimes', 'required', 'date'],
            'abort_type_id' => ['sometimes', 'required', 'exists:abort_types,id'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
