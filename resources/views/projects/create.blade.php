@extends('layouts.app')

@section('content')
    <div class="max-w-lg">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('projects.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">New Project</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Project Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500
                                  @error('name') border-red-400 @enderror"
                           placeholder="e.g. Website Redesign"
                           autofocus>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                                   px-5 py-2 rounded-lg transition-colors">
                        Create Project
                    </button>
                    <a href="{{ route('projects.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
