<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTasksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tasks' => ['required', 'array'],
            'tasks.*' => ['required', 'integer', 'exists:tasks,id'],
        ];
    }
}
