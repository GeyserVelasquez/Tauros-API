<?php

namespace App\Http\Requests\Paddock;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaddockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('paddocks', 'code')
            ],
            'area' => [
                'nullable',
                'numeric',
                'min:0'
            ]
        ];
    }
}
