<?php

namespace App\Http\Requests\RevisionType;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRevisionTypeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $revisionType = $this->route('revisionType');

        return [
            'code' => [
                'required_without:name',
                'string',
                'max:255',
                Rule::unique('revision_types', 'code')->ignore($revisionType)
            ],
            'name' => [
                'required_without:code',
                'string',
                'max:255'
            ],
        ];
    }
}