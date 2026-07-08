<?php

namespace App\Http\Requests\Livestock;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MoveLivestockBatchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'batch_id' => [
                'required',
                'exists:batches,id',
            ],
            'made_at' => [
                'required',
                'date',
            ],
        ];
    }
}
