<?php

namespace App\Http\Requests\Batch;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $batch = $this->route('batch');

        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('batches', 'code')->ignore($batch)
            ],
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255'
            ],
            'paddock_id' => [
                'sometimes',
                'nullable',
                'exists:paddocks,id'
            ]
        ];
    }
}
