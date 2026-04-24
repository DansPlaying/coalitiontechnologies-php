@props([
    'type' => 'success',
    'message',
])

@php
    $styles = match($type) {
        'error'   => 'bg-red-50 border-red-400 text-red-800',
        default   => 'bg-green-50 border-green-400 text-green-800',
    };
@endphp

<div class="border-l-4 px-4 py-3 rounded mb-6 {{ $styles }}" role="alert">
    {{ $message }}
</div>
