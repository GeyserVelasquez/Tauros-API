<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'certificate_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('certificates','certificate_number')
            ],
            'issue_date' => [
                'required',
                'date'
            ],
            'expiry_date' => [
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
