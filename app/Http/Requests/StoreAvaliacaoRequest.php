<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvaliacaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ponto_id' => ['required', 'integer', 'exists:pontos_turisticos,id'],
            'nota' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'ponto_id.required' => 'O ponto turístico é obrigatório.',
            'ponto_id.exists' => 'Ponto turístico não encontrado.',
            'nota.required' => 'A nota é obrigatória.',
            'nota.min' => 'A nota mínima é 1.',
            'nota.max' => 'A nota máxima é 5.',
            'comentario.max' => 'O comentário não pode ter mais de 1000 caracteres.',
        ];
    }
}
