<?php

namespace App\Http\Requests\Abort;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbortRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'livestock_id' => ['required', 'exists:livestock,id'],
            'made_at' => ['required', 'date'],
            'abort_type_id' => ['required', 'exists:abort_types,id'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
