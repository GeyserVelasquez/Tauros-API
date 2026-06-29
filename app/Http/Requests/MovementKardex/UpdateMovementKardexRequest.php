<?php

namespace App\Http\Requests\MovementKardex;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMovementKardexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_type' => ['sometimes', 'required', 'string'],
            'item_id' => ['sometimes', 'required', 'integer'],
            'event_type' => ['sometimes', 'nullable', 'string'],
            'event_id' => ['sometimes', 'nullable', 'integer'],
            'type' => ['sometimes', 'required', Rule::enum(MovementType::class)],
            'quantity' => ['sometimes', 'required', 'integer', 'min:1'],
            'date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
        ];
    }
}
