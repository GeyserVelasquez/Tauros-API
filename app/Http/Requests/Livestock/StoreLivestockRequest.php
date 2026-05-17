<?php

namespace App\Http\Requests\Livestock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\AnimalCategory;

class StoreLivestockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_number' => ['required', 'string', 'max:255', Rule::unique('livestock', 'brand_number')],
            'electronic_code' => ['nullable', 'string', 'max:255', Rule::unique('livestock', 'electronic_code')],
            'name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'entry_date' => ['nullable', 'date', 'after_or_equal:birth_date'],
            'general_comment' => ['nullable', 'string'],
            'tits' => ['required', 'integer', 'min:0'],
            'is_enabled' => ['required','boolean'],
            'is_alive' => ['required','boolean'],
            'entry_cause_id' => ['required', 'exists:entry_causes,id'],
            'state_id' => ['required', 'exists:states,id'],
            'animal_category' => ['required', Rule::enum(AnimalCategory::class)],
            'breed_id' => ['nullable', 'exists:breeds,id'],
            'color_id' => ['nullable', 'exists:colors,id'],
            'classification_id' => ['nullable', 'exists:classifications,id'],
            'owner_id' => ['nullable', 'exists:owners,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'father_id' => ['nullable', 'exists:livestock,id'],
            'mother_id' => ['nullable', 'exists:livestock,id'],
            'adoptive_mother_id' => ['nullable', 'exists:livestock,id'],
            'receiving_mother_id' => ['nullable', 'exists:livestock,id'],
        ];
    }
}
