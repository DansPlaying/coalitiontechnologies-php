@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
        <a href="{{ route('tasks.create', array_filter(['project_id' => $selectedProjectId])) }}"
           class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white
                  text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Task
        </a>
    </div>

    {{-- Project filter --}}
    @if ($projects->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-3">
                <label for="project_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                    Filter by project:
                </label>
                <select id="project_id" name="project_id"
                        class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                    <option value="">All Tasks</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                                {{ $selectedProjectId === $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @if ($selectedProjectId)
                    <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    @endif

    {{-- Task list --}}
    @if ($tasks->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-gray-500 text-sm">No tasks yet.</p>
            <a href="{{ route('tasks.create', array_filter(['project_id' => $selectedProjectId])) }}"
               class="mt-3 inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                Create your first task →
            </a>
        </div>
    @else
        <ul class="space-y-2">
            @foreach ($tasks as $task)
                <li class="bg-white rounded-lg shadow-sm border border-gray-100 flex items-center gap-3 px-4 py-3
                           hover:shadow-md transition-shadow">

                    {{-- Priority badge --}}
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                 ring-1 ring-inset flex-shrink-0 {{ $task->priority->badgeClasses() }}">
                        {{ $task->priority->label() }}
                    </span>

                    {{-- Task name + meta --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $task->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            @if ($task->project && $selectedProjectId === null)
                                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700
                                             px-2 py-0.5 rounded-full font-medium">
                                    {{ $task->project->name }}
                                </span>
                                &middot;
                            @endif
                            Added {{ $task->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                         m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" data-delete-form>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                                             L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        {{ $tasks->links('vendor.pagination.cursor', [
            'nextOffset' => 0,
            'prevOffset' => 0,
        ]) }}
    @endif
@endsection
