@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white
                  text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Project
        </a>
    </div>

    @if ($projects->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <p class="text-gray-500 text-sm">No projects yet.</p>
            <a href="{{ route('projects.create') }}"
               class="mt-3 inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                Create your first project →
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-3">Name</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-3">Tasks</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-3">Created</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $project->name }}</td>
                            <td class="px-6 py-4 text-gray-500">
                                <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $project->tasks_count }}
                                    {{ Str::plural('task', $project->tasks_count) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $project->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('projects.edit', $project) }}"
                                       class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                                     m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('projects.destroy', $project) }}"
                                          data-delete-form>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                                                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
