<?php

namespace App\Http\Requests\Paddock;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaddockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $paddock = $this->route('paddock');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('paddocks', 'code')->ignore($paddock)
            ],
            'area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0'
            ]
        ];
    }
}
