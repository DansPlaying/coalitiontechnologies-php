<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Project $project */
        $project = $this->route('project');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('projects', 'name')->ignore($project->id)],
        ];
    }
}
