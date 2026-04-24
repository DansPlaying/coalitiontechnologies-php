@php
    $messages = array_filter([
        'success' => session('success'),
        'error'   => session('error'),
    ]);
@endphp

@if ($messages)
    <div aria-live="polite" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 pointer-events-none">
        @foreach ($messages as $type => $message)
            <div data-toast
                 class="pointer-events-auto flex items-start gap-3 bg-white rounded-xl shadow-xl
                        border border-gray-100 px-4 py-3 w-80
                        transition-all duration-300 ease-out translate-x-full opacity-0">

                {{-- Icon --}}
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-0.5
                            {{ $type === 'success' ? 'bg-green-50' : 'bg-red-50' }}">
                    @if ($type === 'success')
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    @endif
                </div>

                {{-- Message --}}
                <p class="flex-1 text-sm text-gray-700 pt-0.5">{{ $message }}</p>

                {{-- Close --}}
                <button data-toast-close
                        class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
