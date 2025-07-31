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
            'project_id' => 'required|exists:projects,id',
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|max:50',
        ];
    }
} 