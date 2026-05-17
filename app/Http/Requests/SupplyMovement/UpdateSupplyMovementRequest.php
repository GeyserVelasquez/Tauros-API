<?php

namespace App\Http\Requests\SupplyMovement;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplyMovementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supply_id' => ['sometimes', 'required', 'exists:supplies,id'],
            'type' => ['sometimes', 'required', Rule::enum(MovementType::class)],
            'made_at' => ['sometimes', 'required', 'date'],
            'attributes' => ['nullable', 'array'],
        ];
    }
}
