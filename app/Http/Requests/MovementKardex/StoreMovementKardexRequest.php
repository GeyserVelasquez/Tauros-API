<?php

namespace App\Http\Requests\MovementKardex;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovementKardexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_type' => ['required', 'string'],
            'item_id' => ['required', 'integer'],
            'event_type' => ['nullable', 'string'],
            'event_id' => ['nullable', 'integer'],
            'type' => ['required', Rule::enum(MovementType::class)],
            'quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
