<?php

namespace App\Domains\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'sometimes|string|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do cliente é obrigatório.',
            'name.string' => 'O nome do cliente deve ser um texto válido.',
            'name.max' => 'O nome do cliente não pode ter mais de 255 caracteres.',
            'status.string' => 'O status deve ser um texto válido.',
            'status.in' => 'O status deve ser: active ou inactive.',
        ];
    }
} 