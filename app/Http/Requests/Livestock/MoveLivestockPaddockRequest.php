<?php

namespace App\Http\Requests\Livestock;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MoveLivestockPaddockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'paddock_id' => [
                'required',
                'exists:paddocks,id',
            ],
            'made_at' => [
                'required',
                'date',
            ],
        ];
    }
}
