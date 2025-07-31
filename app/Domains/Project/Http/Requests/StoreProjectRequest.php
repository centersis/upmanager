<?php

namespace App\Domains\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'status' => 'sometimes|string|in:active,inactive',
            'customer_ids' => 'sometimes|array',
            'customer_ids.*' => 'exists:customers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do projeto é obrigatório.',
            'name.string' => 'O nome do projeto deve ser um texto válido.',
            'name.max' => 'O nome do projeto não pode ter mais de 255 caracteres.',
            'group_id.exists' => 'O grupo selecionado não existe.',
            'status.string' => 'O status deve ser um texto válido.',
            'status.in' => 'O status deve ser: active ou inactive.',
            'customer_ids.array' => 'Os clientes devem ser fornecidos como uma lista.',
            'customer_ids.*.exists' => 'Um ou mais clientes selecionados não existem.',
        ];
    }
} 