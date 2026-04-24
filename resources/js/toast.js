const DISMISS_DELAY_MS = 4000;
const TRANSITION_MS = 300;

function show(el) {
    // Double rAF so the initial translate-x-full is painted before we remove it
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            el.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
        });
    });
}

function hide(el) {
    el.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
}

function remove(el) {
    hide(el);
    setTimeout(() => el.remove(), TRANSITION_MS);
}

// ---------------------------------------------------------------------------
// Flash toasts (success / error from session)
// ---------------------------------------------------------------------------

export function initToasts() {
    document.querySelectorAll('[data-toast]').forEach(toast => {
        show(toast);

        const timer = setTimeout(() => remove(toast), DISMISS_DELAY_MS);

        toast.querySelector('[data-toast-close]')?.addEventListener('click', () => {
            clearTimeout(timer);
            remove(toast);
        });
    });
}

// ---------------------------------------------------------------------------
// Delete confirmation dialog
// ---------------------------------------------------------------------------

export function initDeleteConfirmation() {
    const dialog = document.getElementById('delete-confirm');
    if (!dialog) return;

    let pendingForm = null;

    // Intercept every form marked as a delete action
    document.querySelectorAll('[data-delete-form]').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            pendingForm = form;
            show(dialog);
        });
    });

    dialog.querySelector('[data-confirm-delete]').addEventListener('click', () => {
        if (pendingForm) {
            pendingForm.submit();
        }
        hide(dialog);
        pendingForm = null;
    });

    dialog.querySelector('[data-confirm-cancel]').addEventListener('click', () => {
        hide(dialog);
        pendingForm = null;
    });
}
