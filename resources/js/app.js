import './bootstrap';
import { initToasts, initDeleteConfirmation } from './toast';

document.addEventListener('DOMContentLoaded', () => {
    initToasts();
    initDeleteConfirmation();
});
