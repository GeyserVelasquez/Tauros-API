<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],
            'livestock_id' => ['required', 'exists:livestock,id'],
            'commentable_id' => ['nullable', 'integer'],
            'commentable_type' => ['nullable', 'string'],
        ];
    }
}
