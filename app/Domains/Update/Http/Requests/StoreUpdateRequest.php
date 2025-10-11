<?php

namespace App\Domains\Update\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'customer_ids' => 'required|array|min:1',
            'customer_ids.*' => 'exists:customers,id',
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|in:draft,published,archived',
            'views' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'O projeto é obrigatório.',
            'project_id.exists' => 'O projeto selecionado não existe.',
            'customer_ids.required' => 'Pelo menos um cliente deve ser selecionado.',
            'customer_ids.array' => 'Os clientes devem ser fornecidos como uma lista.',
            'customer_ids.min' => 'Pelo menos um cliente deve ser selecionado.',
            'customer_ids.*.exists' => 'Um ou mais clientes selecionados não existem.',
            'title.required' => 'O título da atualização é obrigatório.',
            'title.string' => 'O título deve ser um texto válido.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'caption.string' => 'A legenda deve ser um texto válido.',
            'caption.max' => 'A legenda não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição deve ser um texto válido.',
            'status.string' => 'O status deve ser um texto válido.',
            'status.in' => 'O status deve ser: draft, published ou archived.',
            'views.integer' => 'O número de visualizações deve ser um número inteiro.',
            'views.min' => 'O número de visualizações não pode ser negativo.',
        ];
    }
} 