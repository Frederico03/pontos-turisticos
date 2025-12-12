<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComentarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'texto' => ['required', 'string', 'min:3', 'max:500'],
            'metadata' => ['nullable', 'array'],
            'metadata.language' => ['nullable', 'string', 'max:10'],
            'metadata.device' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'texto.required' => 'O comentário não pode ser vazio.',
            'texto.min' => 'O comentário deve ter no mínimo 3 caracteres.',
            'texto.max' => 'O comentário não pode ter mais de 500 caracteres.',
        ];
    }
}
