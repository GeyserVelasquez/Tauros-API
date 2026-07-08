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
                'nullable',
                'date',
                'after_or_equal:issue_date'
            ],
            'file_path' => [
                'nullable',
                'string',
                'max:255'
            ],
            'file' => [
                'nullable',
                'file',
                'max:5120',
                'mimes:pdf,jpg,jpeg,png'
            ],
            'batch_id' => [
                'nullable',
                'integer',
                'exists:batches,id'
            ],
            'livestock_ids' => [
                'nullable',
                'array'
            ],
            'livestock_ids.*' => [
                'integer',
                'exists:livestock,id'
            ]
        ];
    }
}
