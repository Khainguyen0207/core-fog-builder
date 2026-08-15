import { $ } from '../globals.js';
import { initializeControls } from './forms.js';

function parseConfig(root) {
    const config = root.querySelector('[data-shared-table-config]');
    if (!config) return null;

    try {
        return JSON.parse(config.textContent || '{}');
    } catch (error) {
        console.error('Invalid Shared table configuration.', error);
        return null;
    }
}

function escapeAttribute(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('"', '&quot;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;');
}

function initializeOperationModal(root, dataTable) {
    let operationToDelete = null;

    root.addEventListener('click', event => {
        const operation = event.target.closest('[data-shared-operation]');
        if (!operation || !root.contains(operation) || !operation.dataset.bsToggle) return;

        const target = operation.dataset.bsTarget;
        const modal = target?.startsWith('#') ? document.getElementById(target.slice(1)) : null;
        const form = modal?.querySelector('[data-shared-operation-form]');
        if (!modal || !form || !operation.dataset.bsAction) return;

        form.action = operation.dataset.bsAction;
        const method = form.querySelector('input[name="_method"]');
        if (method) method.value = operation.dataset.bsMethod || 'DELETE';
        const content = modal.querySelector('[data-shared-operation-content]');
        if (content) content.textContent = operation.dataset.bsContent || 'Are you sure?';

        operationToDelete = operation;
    });

    document.addEventListener('submit', async event => {
        const form = event.target.closest('[data-shared-operation-form]');
        if (!form || !operationToDelete) return;

        event.preventDefault();

        const submit = form.querySelector('button[type="submit"]');
        if (submit?.disabled) return;
        if (submit) submit.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: form.querySelector('input[name="_method"]')?.value || 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });
            const payload = await response.json();

            if (!response.ok || payload.error !== false) {
                throw new Error(payload.message || 'Unable to delete this record.');
            }

            window.bootstrap.Modal.getOrCreateInstance(form.closest('.modal')).hide();
            dataTable.row(operationToDelete.closest('tr')).remove().draw(false);
            window.toastManager?.show({ type: 'success', message: payload.message });
            operationToDelete = null;
        } catch (error) {
            window.toastManager?.show({ type: 'error', message: error.message || 'Unable to delete this record.' });
        } finally {
            if (submit) submit.disabled = false;
        }
    });
}

function initializeTable(root) {
    if (root.dataset.sharedInitialized === 'true') return;

    const config = parseConfig(root);
    const table = root.querySelector('[data-shared-table-element]');
    if (!config || !table || !$.fn.DataTable) return;
    initializeControls(root);

    const columns = config.columns.map(column => {
        if (column.render !== '__ROW_CHECKBOX_RENDER__') return column;
        return {
            ...column,
            render: data => `<input class="form-check-input" type="checkbox" data-shared-row-checkbox data-row-id="${escapeAttribute(data)}" aria-label="Select row">`,
        };
    });
    const filter = root.querySelector('[data-shared-table-filter]');
    let selectedIds = [];
    const updateBulk = () => {
        selectedIds = Array.from(root.querySelectorAll('[data-shared-row-checkbox]:checked'), checkbox => checkbox.dataset.rowId);
        root.sharedSelectedIds = selectedIds;
        $(root).data('selected-ids', selectedIds);
        root.querySelectorAll('[data-shared-bulk-count]').forEach(element => {
            element.textContent = String(selectedIds.length);
        });
        root.querySelector('[data-shared-bulk-trigger]')?.classList.toggle('d-none', selectedIds.length === 0);

        const rowCheckboxes = root.querySelectorAll('[data-shared-row-checkbox]');
        const checkedCount = selectedIds.length;
        root.querySelectorAll('[data-shared-select-all]').forEach(checkbox => {
            checkbox.checked = rowCheckboxes.length > 0 && checkedCount === rowCheckboxes.length;
            checkbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
        });
    };

    const dataTable = $(table).DataTable({
        ordering: true,
        order: [[config.orderColumn, 'desc']],
        lengthMenu: [10, 20, 50],
        serverSide: true,
        processing: true,
        scrollX: true,
        ajax: {
            url: config.url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
            data: data => {
                const values = {};
                if (filter) {
                    new FormData(filter).forEach((value, key) => {
                        if (value !== '') values[key] = value;
                    });
                }
                data.search = data.search || {};
                data.search.value = JSON.stringify({ value: data.search.value || '', dataSearch: values });
            },
        },
        columns,
        searching: false,
        dom: '<"dt-top d-flex justify-content-start"l>rt<"dt-bottom"ip>',
    });

    filter?.addEventListener('submit', event => {
        event.preventDefault();
        dataTable.ajax.reload();
    });
    filter?.addEventListener('reset', () => setTimeout(() => dataTable.ajax.reload(), 0));
    root.addEventListener('change', event => {
        if (event.target.matches('[data-shared-select-all]')) {
            root.querySelectorAll('[data-shared-row-checkbox]').forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
        }
        if (event.target.matches('[data-shared-select-all], [data-shared-row-checkbox]')) updateBulk();
    });

    const bulkModal = config.bulkModalId ? document.getElementById(config.bulkModalId) : null;
    bulkModal?.addEventListener('show.bs.modal', () => {
        const count = bulkModal.querySelector('[data-shared-bulk-modal-count]');
        if (count) count.textContent = String(selectedIds.length);
        const ids = bulkModal.querySelector('[data-shared-bulk-ids]');
        ids?.replaceChildren(...selectedIds.map(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            return input;
        }));
    });
    bulkModal?.addEventListener('submit', async event => {
        const form = event.target.closest('form');
        if (!form) return;

        event.preventDefault();

        const submit = form.querySelector('button[type="submit"]');
        if (submit?.disabled) return;
        if (submit) submit.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form),
                credentials: 'same-origin',
            });
            const payload = await response.json().catch(() => ({}));

            if (!response.ok || payload.error !== false) {
                throw new Error(payload.message || 'Unable to delete the selected records.');
            }

            root.querySelectorAll('[data-shared-row-checkbox]').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulk();
            window.bootstrap.Modal.getOrCreateInstance(bulkModal).hide();
            dataTable.ajax.reload(null, false);
            window.toastManager?.show({ type: 'success', message: payload.message });
        } catch (error) {
            window.toastManager?.show({ type: 'error', message: error.message || 'Unable to delete the selected records.' });
        } finally {
            if (submit) submit.disabled = false;
        }
    });

    dataTable.on('draw', updateBulk);

    initializeOperationModal(root, dataTable);
    root.sharedSelectedIds = selectedIds;
    root.sharedDataTable = dataTable;
    root.dataset.sharedInitialized = 'true';
}

export function initializeTables(scope = document) {
    scope.querySelectorAll('[data-shared-table]').forEach(initializeTable);
}
