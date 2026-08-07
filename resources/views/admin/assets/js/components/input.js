(function () {
    function getMenuData() {
        const modal = document.getElementById('menuSearchModal');
        if (!modal) return [];
        const raw = modal.getAttribute('data-menu');
        if (!raw) return [];
        try {
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function normalizeText(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function flattenMenu(items, collector) {
        collector = collector || [];
        items.forEach(function (item) {
            if (!item) return;
            const entry = {
                name: item.name || '',
                icon: item.icon || '',
                url: item.url || '',
                slug: item.slug || ''
            };
            if (entry.name || entry.url) collector.push(entry);
            const children = item.children || item.submenu || item.items || [];
            if (Array.isArray(children) && children.length) {
                flattenMenu(children, collector);
            }
        });
        return collector;
    }

    function setupMenuSearchModal() {
        const trigger = document.getElementById('nav-search-trigger');
        const modalEl = document.getElementById('menuSearchModal');
        const input = document.getElementById('menuSearchInput');
        const results = document.getElementById('menuSearchResults');
        const empty = document.getElementById('menuSearchEmpty');

        if (!trigger || !modalEl || !input || !results || !empty) return;
        if (trigger.dataset.menuSearchInit === '1') return;
        if (!window.bootstrap || !window.bootstrap.Modal) return;

        const allItems = flattenMenu(getMenuData());
        const modal = new window.bootstrap.Modal(modalEl);

        function renderList(items) {
            results.innerHTML = '';
            if (!items.length) {
                empty.classList.remove('d-none');
                return;
            }
            empty.classList.add('d-none');
            items.forEach(function (item) {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 col-lg-4';

                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'w-100 text-start p-3 border-0 shadow rounded bg-white menu-search-item';
                card.setAttribute('data-url', item.url || '');

                const row = document.createElement('div');
                row.className = 'd-flex align-items-center gap-2';

                const icon = document.createElement('i');
                icon.className = item.icon || 'bx bx-circle';

                const name = document.createElement('div');
                name.textContent = item.name || '';

                const link = document.createElement('div');
                link.className = 'text-muted small';
                link.textContent = item.url ? '/' + String(item.url).replace(/^\/+/, '') : '';

                row.appendChild(icon);
                row.appendChild(name);
                card.appendChild(row);
                card.appendChild(link);
                col.appendChild(card);
                results.appendChild(col);
            });
        }

        function filterItems(keyword) {
            const term = normalizeText(keyword);
            if (!term) return allItems;
            return allItems.filter(function (item) {
                return normalizeText(item.name).indexOf(term) > -1 ||
                    normalizeText(item.url).indexOf(term) > -1 ||
                    normalizeText(item.slug).indexOf(term) > -1;
            });
        }

        trigger.addEventListener('click', function () {
            modal.show();
        });

        trigger.addEventListener('focus', function () {
            modal.show();
        });

        document.addEventListener('keydown', function (e) {
            const key = String(e.key || '').toLowerCase();
            if ((e.ctrlKey || e.metaKey) && key === 'k') {
                e.preventDefault();
                modal.show();
            }
        });

        input.addEventListener('input', function () {
            renderList(filterItems(input.value));
        });

        modalEl.addEventListener('shown.bs.modal', function () {
            input.focus();
            renderList(filterItems(input.value));
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            input.value = '';
            renderList(allItems);
        });

        results.addEventListener('click', function (e) {
            const target = e.target.closest('[data-url]');
            if (!target) return;
            const url = target.getAttribute('data-url');
            if (!url) return;
            modal.hide();
            window.location.href = '/' + String(url).replace(/^\/+/, '');
        });

        renderList(allItems);
        trigger.dataset.menuSearchInit = '1';
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupMenuSearchModal);
    } else {
        setupMenuSearchModal();
    }
})();
