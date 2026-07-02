<?php

namespace App\Http\Requests\Birth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AnimalCategory;
use Illuminate\Validation\Rule;

class StoreBirthRequest extends FormRequest
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
            'mother_id' => ['required', 'exists:livestock,id'],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'postbirth_revision_date' => ['required', 'date', 'after_or_equal:birth_date'],
            'birth_type_id' => ['required', 'exists:birth_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],

            // Listado de crías recién nacidas
            'newborns' => ['nullable', 'array'],
            'newborns.*.brand_number' => ['required_with:newborns', 'string', 'max:255', 'unique:livestock,brand_number'],
            'newborns.*.animal_category' => ['required_with:newborns', Rule::enum(AnimalCategory::class)],
            
            // Llaves foráneas dinámicas (ya no se queman en el Backend)
            'newborns.*.entry_cause_id' => ['required_with:newborns', 'exists:entry_causes,id'],
            'newborns.*.state_id' => ['required_with:newborns', 'exists:states,id'],
            'newborns.*.newborn_type_id' => ['required_with:newborns', 'exists:newborn_types,id'],

            // Datos opcionales / nulos de las crías
            'newborns.*.color_id' => ['nullable', 'exists:colors,id'],
            'newborns.*.breed_id' => ['nullable', 'exists:breeds,id'],
            'newborns.*.father_id' => ['nullable', 'exists:livestock,id'],
            'newborns.*.electronic_code' => ['nullable', 'string', 'max:255', 'unique:livestock,electronic_code'],
            'newborns.*.name' => ['nullable', 'string', 'max:255'],
            'newborns.*.general_comment' => ['nullable', 'string'],
        ];
    }
}
