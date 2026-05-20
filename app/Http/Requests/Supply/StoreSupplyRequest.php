<?php

namespace App\Http\Requests\Supply;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('supplies', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'attributes' => ['nullable', 'array'],
            'supply_type_id' => ['required', 'exists:supply_types,id'],
        ];
    }
}
