<?php

namespace App\Http\Requests\EmbrionBatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmbrionBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('embrion_batches', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'mother_id' => ['nullable', 'exists:livestock,id'],
            'father_id' => ['nullable', 'exists:livestock,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
        ];
    }
}
