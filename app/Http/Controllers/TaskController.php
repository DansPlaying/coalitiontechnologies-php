<?php

namespace App\Http\Controllers;

use App\Enums\Priority;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->filled('project_id')
            ? (int) $request->query('project_id')
            : null;

        $tasks = Task::when(
                $selectedProjectId !== null,
                fn ($query) => $query->where('project_id', $selectedProjectId)
            )
            ->with('project')
            ->orderBy('priority')
            ->orderBy('id')
            ->cursorPaginate(10)
            ->appends(array_filter(['project_id' => $selectedProjectId]));

        return view('tasks.index', compact('tasks', 'projects', 'selectedProjectId'));
    }

    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $priorities = Priority::cases();

        $selectedProjectId = $request->filled('project_id')
            ? (int) $request->query('project_id')
            : null;

        return view('tasks.create', compact('projects', 'priorities', 'selectedProjectId'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Task::create($validated);

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $validated['project_id'] ?? null]))
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $projects = Project::orderBy('name')->get();
        $priorities = Priority::cases();

        return view('tasks.edit', compact('task', 'projects', 'priorities'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $task->project_id]))
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;

        $task->delete();

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $projectId]))
            ->with('success', 'Task deleted successfully.');
    }
}
