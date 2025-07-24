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
            'status' => 'sometimes|string|max:50',
            'hash' => 'required|string|unique:projects,hash',
            'customer_ids' => 'array',
            'customer_ids.*' => 'exists:customers,id',
        ];
    }
} 