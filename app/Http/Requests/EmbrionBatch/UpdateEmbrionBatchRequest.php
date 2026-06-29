<?php

namespace App\Http\Requests\EmbrionBatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmbrionBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $embrionBatch = $this->route('embrion_batch');

        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('embrion_batches', 'code')->ignore($embrionBatch)
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'mother_id' => ['sometimes', 'nullable', 'exists:livestock,id'],
            'father_id' => ['sometimes', 'nullable', 'exists:livestock,id'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
        ];
    }
}
