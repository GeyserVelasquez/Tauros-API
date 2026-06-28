<?php

namespace App\Http\Requests\Growth;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrowthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weight' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
            'livestock_id' => ['required', 'exists:livestock,id'],
            'growth_type_id' => ['required', 'exists:growth_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'growthable_type' => ['nullable', 'string'],
            'growthable_id' => ['nullable', 'integer'],
        ];
    }
}
