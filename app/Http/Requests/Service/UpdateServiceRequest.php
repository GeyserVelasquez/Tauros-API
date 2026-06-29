<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'female_id' => ['sometimes', 'required', 'exists:livestock,id'],
            'parentable_type' => ['sometimes', 'required', 'string'],
            'parentable_id' => ['sometimes', 'required', 'integer'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
            'service_type_id' => ['sometimes', 'required', 'exists:service_types,id'],
            'made_at' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
        ];
    }
}
