<?php

namespace App\Http\Requests\DeathCause;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeathCauseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:death_causes,name',
            ],
        ];
    }
}
