<?php

namespace App\Http\Requests\Livestock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\AnimalCategory;

class UpdateLivestockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $livestock = $this->route('livestock');

        return [
            'brand_number' => ['sometimes','required', 'string', 'max:255', Rule::unique('livestock', 'brand_number')->ignore($livestock)],
            'electronic_code' => ['sometimes','nullable', 'string', 'max:255', Rule::unique('livestock', 'electronic_code')],
            'name' => ['sometimes','nullable', 'string', 'max:255'],
            'birth_date' => ['sometimes','nullable', 'date', 'before_or_equal:today'],
            'entry_date' => ['sometimes','nullable', 'date', 'after_or_equal:birth_date'],
            'general_comment' => ['sometimes','nullable', 'string'],
            'tits' => ['sometimes','required', 'integer', 'min:0'],
            'is_enabled' => ['sometimes','required','boolean'],
            'is_alive' => ['sometimes','required','boolean'],
            'entry_cause_id' => ['sometimes','required', 'exists:entry_causes,id'],
            'state_id' => ['sometimes','required', 'exists:states,id'],
            'animal_category' => ['sometimes','required', Rule::enum(AnimalCategory::class)],
            'breed_id' => ['sometimes','nullable', 'exists:breeds,id'],
            'color_id' => ['sometimes','nullable', 'exists:colors,id'],
            'classification_id' => ['sometimes','nullable', 'exists:classifications,id'],
            'owner_id' => ['sometimes','nullable', 'exists:owners,id'],
            'technician_id' => ['sometimes','nullable', 'exists:technicians,id'],
            'father_id' => ['sometimes','nullable', 'exists:livestock,id'],
            'mother_id' => ['sometimes','nullable', 'exists:livestock,id'],
            'adoptive_mother_id' => ['sometimes','nullable', 'exists:livestock,id'],
            'receiving_mother_id' => ['sometimes','nullable', 'exists:livestock,id'],
        ];
    }
}
