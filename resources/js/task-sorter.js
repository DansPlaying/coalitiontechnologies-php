import Sortable from 'sortablejs';

/**
 * Reads the current DOM order of task list items and POSTs the new
 * priority sequence to the server. Fires after every completed drag.
 */
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
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                console.error('Priority sync failed:', data);
            }
        })
        .catch((err) => console.error('Priority sync error:', err));
}

/**
 * Refreshes the priority badge text on each row to match the new
 * visual order without a full page reload.
 */
function updatePriorityBadges(listEl) {
    listEl.querySelectorAll('[data-id]').forEach((el, index) => {
        const badge = el.querySelector('[data-priority-badge]');
        if (badge) {
            badge.textContent = `#${index + 1}`;
        }
    });
}

export function initTaskSorter() {
    const listEl = document.getElementById('task-list');

    // Only activate drag-and-drop when viewing a single project.
    // Allowing reorder in "All Tasks" view would mix priorities across projects.
    if (!listEl || !listEl.dataset.sortable) {
        return;
    }

    Sortable.create(listEl, {
        animation: 150,
        handle: '[data-drag-handle]',
        ghostClass: 'opacity-50',
        chosenClass: 'shadow-lg',
        onEnd() {
            updatePriorityBadges(listEl);
            syncPriorities(listEl);
        },
    });
}
