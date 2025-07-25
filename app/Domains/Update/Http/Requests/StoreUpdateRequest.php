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
            'customer_ids' => 'nullable|array',
            'customer_ids.*' => 'exists:customers,id',
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            'status' => 'sometimes|string|max:50',
            'is_global' => 'sometimes|boolean',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Por padrão, consideramos a atualização como global quando o campo não é enviado
            // Isso evita falhas de validação em chamadas simples à API de criação de updates.
            $isGlobal = $this->input('is_global', true);
            $customerIds = $this->input('customer_ids', []);
            
            // Convert string 'true'/'false' to boolean if needed
            if (is_string($isGlobal)) {
                $isGlobal = $isGlobal === '1' || $isGlobal === 'true';
            }
            
            // Se o campo is_global foi explicitamente definido como "false" e nenhum cliente foi fornecido
            // então deve falhar na validação.
            if ($this->filled('is_global') && !$isGlobal && empty($customerIds)) {
                $validator->errors()->add('customer_ids', 'Selecione pelo menos um cliente para atualização específica.');
            }
            
            // Validate that all customer IDs exist and are valid
            if (!empty($customerIds)) {
                foreach ($customerIds as $customerId) {
                    if (!is_numeric($customerId) || $customerId <= 0) {
                        $validator->errors()->add('customer_ids', 'ID de cliente inválido fornecido.');
                        break;
                    }
                }
            }
        });
    }
} 