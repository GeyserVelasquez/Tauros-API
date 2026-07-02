<?php

namespace App\Http\Requests\Extraction;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtractionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'geneticable_type' => ['required', 'string'],
            'geneticable_id' => ['nullable', 'integer'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'extraction_type_id' => ['required', 'exists:extraction_types,id'],
            'made_at' => ['required', 'date', 'before_or_equal:today'],
            'quantity' => ['required', 'integer', 'min:1'],

            // Campos para la creación dinámica del lote si geneticable_id es nulo
            'code' => ['required_without:geneticable_id', 'nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'female_id' => ['required_without:geneticable_id', 'nullable', 'exists:livestock,id'],
            'male_id' => ['nullable', 'exists:livestock,id'],
            'livestock_id' => ['nullable', 'exists:livestock,id'],
        ];
    }
}
