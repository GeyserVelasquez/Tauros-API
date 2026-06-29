<?php

namespace App\Http\Requests\Growth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGrowthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weight' => ['sometimes', 'required', 'numeric', 'min:0'],
            'height' => ['sometimes', 'required', 'numeric', 'min:0'],
            'made_at' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'livestock_id' => ['sometimes', 'required', 'exists:livestock,id'],
            'growth_type_id' => ['sometimes', 'required', 'exists:growth_types,id'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
            'growthable_type' => ['sometimes', 'nullable', 'string'],
            'growthable_id' => ['sometimes', 'nullable', 'integer'],
        ];
    }
}
