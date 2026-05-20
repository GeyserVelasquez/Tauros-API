<?php

namespace App\Http\Requests\Technician;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTechnicianRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $technician = $this->route('technician');

        return [
            'code' => [
                'required_without_all:name,telephone',
                'string',
                'max:255',
                Rule::unique('technicians', 'code')->ignore($technician)
            ],
            'name' => [
                'required_without_all:code,telephone',
                'string',
                'max:255'
            ],
            'telephone' => [
                'required_without_all:code,name',
                'string',
                'max:255'
            ],
        ];
    }
}
