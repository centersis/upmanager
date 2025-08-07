<?php

namespace App\Domains\Update\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'nullable|exists:projects,id',
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|in:pending,published,draft,archived',
            'hash' => 'sometimes|string|max:255|unique:updates,hash',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.exists' => 'O projeto selecionado não existe.',
            'customer_id.required' => 'O cliente é obrigatório.',
            'customer_id.exists' => 'O cliente selecionado não existe.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais que 255 caracteres.',
            'caption.max' => 'A legenda não pode ter mais que 255 caracteres.',
            'status.in' => 'O status deve ser: pending, published, draft ou archived.',
            'hash.unique' => 'Este hash já está em uso.',
        ];
    }
} 