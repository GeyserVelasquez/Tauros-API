<?php

namespace App\Http\Requests\Newborn;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewbornRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'birth_id' => ['required', 'exists:births,id'],
            'newborn_type_id' => ['required', 'exists:newborn_types,id'],
            'livestock_id' => ['required', 'exists:livestock,id'],
        ];
    }
}
