import './bootstrap';
import '@hotwired/turbo';
import { initToasts, initDeleteConfirmation } from './toast';
import { initTaskSorter } from './task-sorter';

// Runs once after the initial DOM is ready — event delegation inside
// survives all subsequent Turbo body swaps
document.addEventListener('DOMContentLoaded', () => {
    initDeleteConfirmation();
});

// Runs after every Turbo navigation (and on first load) because
// toasts and sortable lists are freshly rendered on each response
document.addEventListener('turbo:load', () => {
    initToasts();
    initTaskSorter();
});
