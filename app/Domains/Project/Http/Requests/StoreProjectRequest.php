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
            'status' => 'sometimes|string|max:50',
            'hash' => 'nullable|string|unique:projects,hash',
            'customer_ids' => 'array',
            'customer_ids.*' => 'exists:customers,id',
        ];
    }
} 