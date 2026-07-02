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
            'batch_id' => ['nullable', 'integer'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'extraction_type_id' => ['required', 'exists:extraction_types,id'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
            'quantity' => ['required', 'integer', 'min:1'],

            // Campos para la creación dinámica del lote si batch_id es nulo
            'code' => ['required_without:batch_id', 'nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'female_id' => ['required_without:batch_id', 'nullable', 'exists:livestock,id'],
            'male_id' => ['nullable', 'exists:livestock,id'],
            'livestock_id' => ['nullable', 'exists:livestock,id'],
        ];
    }
}
