<?php

namespace App\Domains\Update\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'sometimes|required|exists:projects,id',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'title' => 'sometimes|required|string|max:255',
            'caption' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:10000',
            'status' => 'sometimes|string|in:draft,published,archived',
            'views' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'O projeto é obrigatório.',
            'project_id.exists' => 'O projeto selecionado não existe.',
            'customer_id.required' => 'O cliente é obrigatório.',
            'customer_id.exists' => 'O cliente selecionado não existe.',
            'title.required' => 'O título da atualização é obrigatório.',
            'title.string' => 'O título deve ser um texto válido.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'caption.string' => 'A legenda deve ser um texto válido.',
            'caption.max' => 'A legenda não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição deve ser um texto válido.',
            'description.max' => 'A descrição não pode ter mais de 10.000 caracteres.',
            'status.string' => 'O status deve ser um texto válido.',
            'status.in' => 'O status deve ser: draft, published ou archived.',
            'views.integer' => 'O número de visualizações deve ser um número inteiro.',
            'views.min' => 'O número de visualizações não pode ser negativo.',
        ];
    }
} 