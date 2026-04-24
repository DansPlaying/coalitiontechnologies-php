<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReorderTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        // Strip the empty ?project_id= param so the URL stays clean
        if ($request->has('project_id') && !$request->filled('project_id')) {
            return redirect()->route('tasks.index');
        }

        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->filled('project_id')
            ? (int) $request->query('project_id')
            : null;

        $query = Task::with('project')
            ->when($selectedProjectId !== null, fn ($q) => $q->where('project_id', $selectedProjectId))
            ->orderBy('priority')
            ->orderBy('id');

        // Load all tasks when scoped to one project (drag-and-drop needs the full list).
        // Use cursor pagination only for the unfiltered "All Tasks" view.
        $startIndex = (int) $request->query('start', 0);

        $tasks = $selectedProjectId !== null
            ? $query->get()
            : $query->cursorPaginate(15);

        return view('tasks.index', compact('tasks', 'projects', 'selectedProjectId', 'startIndex'));
    }

    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->filled('project_id')
            ? (int) $request->query('project_id')
            : null;

        return view('tasks.create', compact('projects', 'selectedProjectId'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $projectId = $validated['project_id'] ?? null;

        $validated['priority'] = Task::where('project_id', $projectId)->max('priority') + 1;

        Task::create($validated);

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $projectId]))
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $projects = Project::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $validated  = $request->validated();
        $newProject = $validated['project_id'] ?? null;

        if ($newProject !== $task->project_id) {
            $oldProject = $task->project_id;

            $task->update([
                'name'       => $validated['name'],
                'project_id' => $newProject,
                'priority'   => Task::where('project_id', $newProject)->max('priority') + 1,
            ]);

            $this->resequence($oldProject);
        } else {
            $task->update(['name' => $validated['name']]);
        }

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $task->project_id]))
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;

        $task->delete();

        $this->resequence($projectId);

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $projectId]))
            ->with('success', 'Task deleted successfully.');
    }

    public function reorder(ReorderTasksRequest $request): JsonResponse
    {
        $ids = $request->input('tasks');

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Task::where('id', $id)->update(['priority' => $index + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    private function resequence(int|null $projectId): void
    {
        Task::where('project_id', $projectId)
            ->orderBy('priority')
            ->orderBy('id')
            ->get()
            ->each(function (Task $task, int $i) {
                $task->timestamps = false;
                $task->priority   = $i + 1;
                $task->save();
            });
    }
}
