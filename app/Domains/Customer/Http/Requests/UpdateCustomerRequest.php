<?php

namespace App\Domains\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|string|in:active,inactive',
            'contacts' => 'sometimes|array',
            'contacts.*.id' => 'sometimes|integer|exists:customer_contacts,id',
            'contacts.*.name' => 'required_with:contacts.*|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*._destroy' => 'sometimes|boolean',
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
            'contacts.array' => 'Os contatos devem ser um array válido.',
            'contacts.*.id.integer' => 'O ID do contato deve ser um número válido.',
            'contacts.*.id.exists' => 'O contato não foi encontrado.',
            'contacts.*.name.required_with' => 'O nome do contato é obrigatório.',
            'contacts.*.name.string' => 'O nome do contato deve ser um texto válido.',
            'contacts.*.name.max' => 'O nome do contato não pode ter mais de 255 caracteres.',
            'contacts.*.phone.string' => 'O telefone deve ser um texto válido.',
            'contacts.*.phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'contacts.*.email.email' => 'O email deve ter um formato válido.',
            'contacts.*.email.max' => 'O email não pode ter mais de 255 caracteres.',
            'contacts.*._destroy.boolean' => 'O campo de remoção deve ser verdadeiro ou falso.',
        ];
    }
} 