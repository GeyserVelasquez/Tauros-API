<?php

namespace App\Http\Requests\DeathCause;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeathCauseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('death_cause')?->id;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('death_causes', 'name')->ignore($id),
            ],
        ];
    }
}
