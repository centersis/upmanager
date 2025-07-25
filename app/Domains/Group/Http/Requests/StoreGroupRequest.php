<?php

namespace App\Domains\Group\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do grupo é obrigatório.',
            'name.unique' => 'Já existe um grupo com este nome.',
            'name.max' => 'O nome do grupo não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 500 caracteres.',
            'color.regex' => 'A cor deve estar no formato hexadecimal válido (ex: #FF5733).',
        ];
    }
} 