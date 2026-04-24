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
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->filled('project_id')
            ? (int) $request->query('project_id')
            : null;

        $offset = (int) $request->query('offset', 0);

        $tasks = Task::when(
                $selectedProjectId !== null,
                fn ($query) => $query->where('project_id', $selectedProjectId)
            )
            ->with('project')
            ->orderBy('priority')
            ->orderBy('id') // tiebreaker ensures a stable cursor across equal priorities
            ->cursorPaginate(10)
            ->appends(array_filter(['project_id' => $selectedProjectId]));

        return view('tasks.index', compact('tasks', 'projects', 'selectedProjectId', 'offset'));
    }

    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        // Pre-select the project when arriving from a filtered view
        $selectedProjectId = $request->filled('project_id')
            ? (int) $request->query('project_id')
            : null;

        return view('tasks.create', compact('projects', 'selectedProjectId'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Place new tasks at the bottom of their project scope
        $validated['priority'] = Task::where('project_id', $validated['project_id'] ?? null)
            ->max('priority') + 1;

        Task::create($validated);

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $validated['project_id'] ?? null]))
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $projects = Project::orderBy('name')->get();

        $maxPriority = Task::where('project_id', $task->project_id)->count();

        return view('tasks.edit', compact('task', 'projects', 'maxPriority'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $validated = $request->validated();

        $newProjectId = $validated['project_id'] ?? null;
        $projectChanged = $task->project_id !== $newProjectId;

        DB::transaction(function () use ($task, $validated, $newProjectId, $projectChanged) {
            if ($projectChanged) {
                // Remove the task from its old project and close the gap
                $this->resequence($task->project_id, $task->priority);

                // Append to the bottom of the new project
                $validated['priority'] = Task::where('project_id', $newProjectId)->max('priority') + 1;

            } elseif (isset($validated['priority'])) {
                // Same project, manual priority change — shift neighbours to make room
                $totalInProject = Task::where('project_id', $newProjectId)->count();
                $newPriority = max(1, min((int) $validated['priority'], $totalInProject));

                if ($newPriority !== $task->priority) {
                    $this->insertAtPriority($task, $newPriority);
                }

                $validated['priority'] = $newPriority;
            }

            $task->update($validated);
        });

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $task->project_id]))
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;
        $deletedPriority = $task->priority;

        $task->delete();

        $this->resequence($projectId, $deletedPriority);

        return redirect()
            ->route('tasks.index', array_filter(['project_id' => $projectId]))
            ->with('success', 'Task deleted successfully.');
    }

    public function reorder(ReorderTasksRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('tasks') as $index => $taskId) {
                Task::where('id', $taskId)->update(['priority' => $index + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Close the priority gap left by a deleted or moved task so the
     * sequence within a project scope stays contiguous (1, 2, 3, …).
     */
    private function resequence(?int $projectId, int $deletedPriority): void
    {
        Task::where('project_id', $projectId)
            ->where('priority', '>', $deletedPriority)
            ->decrement('priority');
    }

    /**
     * Shift neighbouring tasks to make room at $newPriority, keeping the
     * sequence contiguous. Moving up increments the tasks in between;
     * moving down decrements them.
     */
    private function insertAtPriority(Task $task, int $newPriority): void
    {
        $oldPriority = $task->priority;

        if ($newPriority < $oldPriority) {
            Task::where('project_id', $task->project_id)
                ->whereBetween('priority', [$newPriority, $oldPriority - 1])
                ->increment('priority');
        } else {
            Task::where('project_id', $task->project_id)
                ->whereBetween('priority', [$oldPriority + 1, $newPriority])
                ->decrement('priority');
        }
    }
}
