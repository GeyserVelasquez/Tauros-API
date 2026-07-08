<?php

namespace App\Http\Requests\Batch;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('batches', 'code')
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'paddock_id' => [
                'nullable', 'exists:paddocks,id'
            ]
        ];
    }
}
