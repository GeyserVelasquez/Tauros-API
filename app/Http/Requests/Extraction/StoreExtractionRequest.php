<?php

namespace App\Http\Requests\Extraction;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtractionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'batch_type' => ['required', 'string'],
            'batch_id' => ['required', 'integer'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'extraction_type_id' => ['required', 'exists:extraction_types,id'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
