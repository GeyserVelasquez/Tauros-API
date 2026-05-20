<?php

namespace App\Http\Requests\SupplyMovement;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplyMovementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supply_id' => ['required', 'exists:supplies,id'],
            'type' => ['required', Rule::enum(MovementType::class)],
            'made_at' => ['required', 'date'],
            'attributes' => ['nullable', 'array'],
        ];
    }
}
