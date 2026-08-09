import { $, Quill } from '../globals.js';

function initializeEditor(root) {
    if (root.dataset.sharedInitialized === 'true') return;

    const editorElement = root.querySelector('[data-shared-editor-surface]');
    const inputElement = root.querySelector('[data-shared-editor-input]');
    if (!editorElement || !inputElement) return;

    const editor = new Quill(editorElement, {
        bounds: editorElement,
        placeholder: root.dataset.sharedEditorPlaceholder || 'Type Something...',
        theme: 'snow',
    });

    root.closest('[data-shared-form]')?.addEventListener('submit', () => {
        inputElement.value = editor.root.innerHTML;
    });
    root.sharedEditor = editor;
    root.dataset.sharedInitialized = 'true';
}

function initializeFilePreview(root) {
    if (root.dataset.sharedInitialized === 'true') return;

    const input = root.querySelector('[data-shared-file-input]');
    const image = root.querySelector('[data-shared-file-image]');
    if (!input || !image) return;

    let objectUrl;
    input.addEventListener('change', () => {
        const file = input.files?.[0];
        if (!file?.type.startsWith('image/')) return;
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = URL.createObjectURL(file);
        image.src = objectUrl;
    });
    root.querySelector('[data-shared-file-remove]')?.addEventListener('click', () => {
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = undefined;
        input.value = '';
        image.src = 'https://placehold.co/150x150';
    });
    root.dataset.sharedInitialized = 'true';
}

function initializeForm(form) {
    if (form.dataset.sharedInitialized === 'true') return;

    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    initializeControls(form);
    form.querySelectorAll('[data-shared-editor]').forEach(initializeEditor);
    form.querySelectorAll('[data-shared-file-preview]').forEach(initializeFilePreview);
    form.dataset.sharedInitialized = 'true';
}

export function initializeControls(root) {
    root.querySelectorAll('select.selectpicker').forEach(select => {
        if (select.dataset.sharedSelectInitialized === 'true') return;
        $(select).selectpicker();
        select.dataset.sharedSelectInitialized = 'true';
    });

    root.querySelectorAll('input.daterangepicker-single').forEach(input => {
        if (input.dataset.sharedDateInitialized === 'true') return;
        $(input).daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            locale: { format: 'YYYY-MM-DD HH:mm:ss' },
        });
        input.dataset.sharedDateInitialized = 'true';
    });

    root.querySelectorAll('input.daterangepicker-range').forEach(input => {
        if (input.dataset.sharedDateInitialized === 'true') return;
        $(input).daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 5,
            autoApply: true,
            locale: { format: 'HH:mm' },
            isInvalidDate: date => !date.isSame(window.moment(), 'day'),
        });
        input.dataset.sharedDateInitialized = 'true';
    });

}

export function initializeForms(scope = document) {
    scope.querySelectorAll('[data-shared-form]').forEach(initializeForm);
    scope.querySelectorAll('[data-shared-editor]').forEach(initializeEditor);
    scope.querySelectorAll('[data-shared-file-preview]').forEach(initializeFilePreview);
}
