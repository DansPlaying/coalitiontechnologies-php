<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Task Manager') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-gray-900 shadow-md">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('tasks.index') }}" class="text-white font-bold text-xl tracking-tight">
                    Task Manager
                </a>
                <div class="flex gap-6">
                    <a href="{{ route('tasks.index') }}"
                       class="text-gray-300 hover:text-white text-sm font-medium transition-colors
                              {{ request()->routeIs('tasks.*') ? 'text-white border-b-2 border-blue-400 pb-0.5' : '' }}">
                        Tasks
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="text-gray-300 hover:text-white text-sm font-medium transition-colors
                              {{ request()->routeIs('projects.*') ? 'text-white border-b-2 border-blue-400 pb-0.5' : '' }}">
                        Projects
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <x-toast />
    <x-delete-confirm />

</body>
</html>
