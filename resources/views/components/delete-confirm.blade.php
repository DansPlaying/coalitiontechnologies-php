<div id="delete-confirm"
     class="fixed bottom-4 right-4 z-50 w-80 bg-white rounded-xl shadow-xl border border-gray-100 p-4
            transition-all duration-300 ease-out translate-x-full opacity-0 pointer-events-none">

    <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-9 h-9 bg-red-50 rounded-full flex items-center justify-center mt-0.5">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                         m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900">Delete this item?</p>
            <p class="text-xs text-gray-400 mt-0.5">This action cannot be undone.</p>

            <div class="flex gap-2 mt-3">
                <button data-confirm-delete
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium
                               px-3 py-1.5 rounded-lg transition-colors">
                    Delete
                </button>
                <button data-confirm-cancel
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium
                               px-3 py-1.5 rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
