<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'female_id' => ['required', 'exists:livestock,id'],
            'parentable_type' => ['required', 'string'],
            'parentable_id' => ['required', 'integer'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'service_type_id' => ['required', 'exists:service_types,id'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
