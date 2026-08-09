import './plugins.js';
import { initializeForms } from './runtime/forms.js';
import { initializeLayout } from './runtime/layout.js';
import { initializeMenuSearches } from './runtime/menu-search.js';
import { initializeTables } from './runtime/tables.js';
import { initializeToasts } from './runtime/toasts.js';

function initialize(scope = document) {
    initializeLayout(scope);
    initializeForms(scope);
    initializeTables(scope);
    initializeMenuSearches(scope);
    initializeToasts(scope);
}

window.FigureAdminShared = Object.freeze({ initialize });

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initialize(), { once: true });
} else {
    initialize();
}
