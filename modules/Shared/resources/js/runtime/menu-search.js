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
        const seenItems = new Set();
        items = flattenMenu(JSON.parse(registry.textContent || '[]')).filter(item => {
            const key = item.url || item.name;
            if (seenItems.has(key)) return false;
            seenItems.add(key);
            return true;
        });
    } catch (error) {
        console.error('Invalid Shared menu registry.', error);
        return;
    }
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    let activeIndex = 0;
    const filteredItems = () => {
        const term = normalizeText(input.value);
        return !term ? items : items.filter(item => [item.name, item.url, item.slug].some(value => normalizeText(value).includes(term)));
    };
    const render = () => {
        const matches = filteredItems();
        activeIndex = Math.min(activeIndex, Math.max(matches.length - 1, 0));
        results.replaceChildren(...matches.map(item => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'menu-search-item';
            button.setAttribute('role', 'option');
            button.setAttribute('aria-selected', 'false');
            button.dataset.sharedMenuUrl = item.url;
            const iconWrap = document.createElement('span');
            iconWrap.className = 'menu-search-item-icon';
            const icon = document.createElement('i');
            icon.className = item.icon || 'bx bx-circle';
            icon.setAttribute('aria-hidden', 'true');
            iconWrap.append(icon);
            const content = document.createElement('span');
            content.className = 'menu-search-item-content';
            const name = document.createElement('div');
            name.className = 'menu-search-item-name';
            name.textContent = item.name;
            const url = document.createElement('div');
            url.className = 'menu-search-item-url';
            url.textContent = item.url ? `/${String(item.url).replace(/^\/+/, '')}` : '';
            const arrow = document.createElement('i');
            arrow.className = 'bx bx-right-arrow-alt menu-search-item-arrow';
            arrow.setAttribute('aria-hidden', 'true');
            content.append(name, url);
            button.append(iconWrap, content, arrow);
            return button;
        }));
        updateActiveItem();
        empty.classList.toggle('d-none', matches.length > 0);
    };
    const updateActiveItem = () => {
        results.querySelectorAll('[data-shared-menu-url]').forEach((item, index) => {
            const isActive = index === activeIndex;
            item.classList.toggle('is-active', isActive);
            item.setAttribute('aria-selected', String(isActive));
        });
    };
    const navigateToActiveItem = () => {
        const activeItem = results.querySelectorAll('[data-shared-menu-url]')[activeIndex];
        if (!activeItem?.dataset.sharedMenuUrl) return;
        modal.hide();
        window.location.assign(`/${activeItem.dataset.sharedMenuUrl.replace(/^\/+/, '')}`);
    };

    trigger.addEventListener('click', () => modal.show());
    document.addEventListener('keydown', event => {
        if ((event.ctrlKey || event.metaKey) && String(event.key).toLowerCase() === 'k') {
            event.preventDefault();
            modal.show();
        }
    });
    input.addEventListener('input', () => {
        activeIndex = 0;
        render();
    });
    input.addEventListener('keydown', event => {
        const matches = filteredItems();
        if (!matches.length) return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            activeIndex = (activeIndex + 1) % matches.length;
            updateActiveItem();
        }
        if (event.key === 'ArrowUp') {
            event.preventDefault();
            activeIndex = (activeIndex - 1 + matches.length) % matches.length;
            updateActiveItem();
        }
        if (event.key === 'Enter') {
            event.preventDefault();
            navigateToActiveItem();
        }
    });
    modalElement.addEventListener('shown.bs.modal', () => {
        input.focus();
        render();
    });
    modalElement.addEventListener('hidden.bs.modal', () => {
        input.value = '';
        activeIndex = 0;
        render();
        trigger.focus();
    });
    results.addEventListener('click', event => {
        const item = event.target.closest('[data-shared-menu-url]');
        if (!item?.dataset.sharedMenuUrl) return;
        activeIndex = [...results.querySelectorAll('[data-shared-menu-url]')].indexOf(item);
        navigateToActiveItem();
    });
    root.dataset.sharedInitialized = 'true';
}

export function initializeMenuSearches(scope = document) {
    scope.querySelectorAll('[data-shared-menu-search]').forEach(initializeMenuSearch);
}
