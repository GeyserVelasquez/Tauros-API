<?php

namespace App\Http\Requests\Birth;

use App\Enums\State;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AnimalCategory;
use Illuminate\Validation\Rule;

class UpdateBirthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Datos generales del parto
            'mother_id' => ['sometimes', 'required', 'exists:livestock,id'],
            'birth_date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'postbirth_revision_date' => ['sometimes', 'required', 'date', 'after_or_equal:birth_date'],
            'birth_type_id' => ['sometimes', 'required', 'exists:birth_types,id'],
            'technician_id' => ['sometimes', 'nullable', 'exists:technicians,id'],

            'newborns' => ['nullable', 'array'],
            'newborns.*.brand_number' => ['required_with:newborns', 'string', 'max:255', 'unique:livestock,brand_number'],
            'newborns.*.animal_category' => ['required_with:newborns', Rule::enum(AnimalCategory::class)],

            'newborns.*.entry_cause_id' => ['required_with:newborns', 'exists:entry_causes,id'],
            'newborns.*.state' => ['required_with:newborns', Rule::enum(State::class)],
            'newborns.*.newborn_type_id' => ['required_with:newborns', 'exists:newborn_types,id'],

            'newborns.*.color_id' => ['nullable', 'exists:colors,id'],
            'newborns.*.breed_id' => ['nullable', 'exists:breeds,id'],
            'newborns.*.father_id' => ['nullable', 'exists:livestock,id'],
            'newborns.*.electronic_code' => ['nullable', 'string', 'max:255', 'unique:livestock,electronic_code'],
            'newborns.*.name' => ['nullable', 'string', 'max:255'],
            'newborns.*.general_comment' => ['nullable', 'string'],
        ];
    }
}
