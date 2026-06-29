<?php

namespace App\Http\Requests\Extraction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExtractionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'batch_type' => ['sometimes', 'required', 'string'],
            'batch_id' => ['sometimes', 'required', 'integer'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],
            'extraction_type_id' => ['sometimes', 'required', 'exists:extraction_types,id'],
            'made_at' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
        ];
    }
}
