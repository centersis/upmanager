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
            'project_id' => 'sometimes|exists:projects,id',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'title' => 'sometimes|string|max:255',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|max:50',
            'views' => 'sometimes|integer|min:0',
        ];
    }
} 