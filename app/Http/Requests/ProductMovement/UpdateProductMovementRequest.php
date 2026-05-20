<?php

namespace App\Http\Requests\ProductMovement;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductMovementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'required', 'exists:products,id'],
            'type' => ['sometimes', 'required', Rule::enum(MovementType::class)],
            'made_at' => ['sometimes', 'required', 'date'],
            'attributes' => ['nullable', 'array'],
        ];
    }
}
