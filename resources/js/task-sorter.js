import Sortable from 'sortablejs';

function syncPriorities(listEl) {
    const taskIds = [...listEl.querySelectorAll('[data-id]')].map((el) => el.dataset.id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/tasks/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ tasks: taskIds }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (!data.success) console.error('Reorder failed:', data);
        })
        .catch((err) => console.error('Reorder error:', err));
}

function updatePriorityBadges(listEl) {
    listEl.querySelectorAll('[data-id]').forEach((el, index) => {
        const badge = el.querySelector('[data-priority-badge]');
        if (badge) badge.textContent = index + 1;
    });
}

export function initTaskSorter() {
    const listEl = document.getElementById('task-list');

    // Only activate drag-and-drop when viewing a single project.
    // Allowing reorder across "All Tasks" would mix priorities between projects.
    if (!listEl || !listEl.hasAttribute('data-sortable')) return;

    // Guard against Turbo re-initialising the same element on repeated visits
    if (listEl._sortable) return;

    listEl._sortable = Sortable.create(listEl, {
        animation: 150,
        handle: '[data-drag-handle]',
        ghostClass: 'opacity-40',
        chosenClass: 'shadow-lg',
        onEnd() {
            updatePriorityBadges(listEl);
            syncPriorities(listEl);
        },
    });
}
