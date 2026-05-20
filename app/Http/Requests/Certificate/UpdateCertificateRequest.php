<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $certificate = $this->route('certificate');

        return [
            'certificate_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('certificates')->ignore($certificate),
            ],
            'issue_date' => [
                'sometimes',
                'required',
                'date'
            ],
            'expiry_date' => [
                'sometimes',
                'required',
                'date',
                'after_or_equal:issue_date'
            ],
            'file_path' => [
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }
}
