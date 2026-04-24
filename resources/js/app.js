import './bootstrap';
import { initTaskSorter } from './task-sorter';
import { initToasts, initDeleteConfirmation } from './toast';

document.addEventListener('DOMContentLoaded', () => {
    initToasts();
    initDeleteConfirmation();
    initTaskSorter();
});
