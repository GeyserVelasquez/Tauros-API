<?php

namespace App\Http\Requests\SemenBatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSemenBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('semen_batches', 'code')->ignore($this->route('semen_batch'))
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'livestock_id' => ['sometimes', 'nullable', 'exists:livestock,id'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
        ];
    }
}
