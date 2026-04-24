@if ($paginator->hasPages())
@php
    $nextUrl = $paginator->nextPageUrl()     ? $paginator->nextPageUrl()     . '&offset=' . $nextOffset : null;
    $prevUrl = $paginator->previousPageUrl() ? $paginator->previousPageUrl() . '&offset=' . $prevOffset : null;
@endphp
    <nav class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200" aria-label="Pagination">
        <p class="text-sm text-gray-500">
            Showing <span class="font-medium text-gray-700">{{ $paginator->count() }}</span>
            {{ Str::plural('task', $paginator->count()) }} per page
        </p>

        <div class="flex items-center gap-2">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-300
                             bg-white border border-gray-200 rounded-lg cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous
                </span>
            @else
                <a href="{{ $prevUrl }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700
                          bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous
                </a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $nextUrl }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700
                          bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-300
                             bg-white border border-gray-200 rounded-lg cursor-not-allowed select-none">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
