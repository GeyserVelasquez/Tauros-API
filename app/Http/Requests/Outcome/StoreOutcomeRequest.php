<?php

namespace App\Http\Requests\Outcome;

use Illuminate\Foundation\Http\FormRequest;

class StoreOutcomeRequest extends FormRequest
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
            'made_at' => ['required', 'date'],
            'outcome_type_id' => ['required', 'exists:outcome_types,id'],
        ];
    }
}
