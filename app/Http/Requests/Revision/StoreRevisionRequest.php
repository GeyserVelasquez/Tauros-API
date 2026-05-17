<?php

namespace App\Http\Requests\Revision;

use App\Enums\RevisionResult;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRevisionRequest extends FormRequest
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
            'revision_result' => ['required', Rule::Enum(RevisionResult::class)],
            'revision_type_id' => ['required', 'exists:revision_types,id'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
        ];
    }
}
