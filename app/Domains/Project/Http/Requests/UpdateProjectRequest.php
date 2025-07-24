<?php

namespace App\Domains\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $projectId = $this->route('project')?->id;
        return [
            'name' => 'sometimes|string|max:255',
            'group_id' => 'sometimes|nullable|exists:groups,id',
            'status' => 'sometimes|string|max:50',
            'hash' => 'sometimes|string|unique:projects,hash,' . $projectId,
            'customer_ids' => 'array',
            'customer_ids.*' => 'exists:customers,id',
        ];
    }
} 