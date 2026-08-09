import { bootstrap } from '../globals.js';

function normalizeText(value) {
    return String(value || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function flattenMenu(items, result = []) {
    items.forEach(item => {
        if (!item) return;
        if (item.name || item.url) result.push({
            name: item.name || '',
            icon: item.icon || '',
            url: item.url || '',
            slug: item.slug || '',
        });
        flattenMenu(Array.isArray(item.children) ? item.children : [], result);
    });
    return result;
}

function initializeMenuSearch(root) {
    if (root.dataset.sharedInitialized === 'true') return;

    const registry = root.querySelector('[data-shared-menu-registry]');
    const trigger = root.querySelector('[data-shared-menu-search-trigger]');
    const modalElement = root.querySelector('[data-shared-menu-search-modal]');
    const input = root.querySelector('[data-shared-menu-search-input]');
    const results = root.querySelector('[data-shared-menu-search-results]');
    const empty = root.querySelector('[data-shared-menu-search-empty]');
    if (!registry || !trigger || !modalElement || !input || !results || !empty) return;

    let items;
    try {
        items = flattenMenu(JSON.parse(registry.textContent || '[]'));
    } catch (error) {
        console.error('Invalid Shared menu registry.', error);
        return;
    }
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    const filteredItems = () => {
        const term = normalizeText(input.value);
        return !term ? items : items.filter(item => [item.name, item.url, item.slug].some(value => normalizeText(value).includes(term)));
    };
    const render = () => {
        const matches = filteredItems();
        results.replaceChildren(...matches.map(item => {
            const column = document.createElement('div');
            column.className = 'col-12 col-md-6 col-lg-4';
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'w-100 text-start p-3 border-0 shadow rounded bg-white menu-search-item';
            button.dataset.sharedMenuUrl = item.url;
            const label = document.createElement('div');
            label.className = 'd-flex align-items-center gap-2';
            const icon = document.createElement('i');
            icon.className = item.icon || 'bx bx-circle';
            const name = document.createElement('div');
            name.textContent = item.name;
            const url = document.createElement('div');
            url.className = 'text-muted small';
            url.textContent = item.url ? `/${String(item.url).replace(/^\/+/, '')}` : '';
            label.append(icon, name);
            button.append(label, url);
            column.append(button);
            return column;
        }));
        empty.classList.toggle('d-none', matches.length > 0);
    };

    trigger.addEventListener('click', () => modal.show());
    trigger.addEventListener('focus', () => modal.show());
    document.addEventListener('keydown', event => {
        if ((event.ctrlKey || event.metaKey) && String(event.key).toLowerCase() === 'k') {
            event.preventDefault();
            modal.show();
        }
    });
    input.addEventListener('input', render);
    modalElement.addEventListener('shown.bs.modal', () => {
        input.focus();
        render();
    });
    modalElement.addEventListener('hidden.bs.modal', () => {
        input.value = '';
        render();
    });
    results.addEventListener('click', event => {
        const item = event.target.closest('[data-shared-menu-url]');
        if (!item?.dataset.sharedMenuUrl) return;
        modal.hide();
        window.location.assign(`/${item.dataset.sharedMenuUrl.replace(/^\/+/, '')}`);
    });
    root.dataset.sharedInitialized = 'true';
}

export function initializeMenuSearches(scope = document) {
    scope.querySelectorAll('[data-shared-menu-search]').forEach(initializeMenuSearch);
}
