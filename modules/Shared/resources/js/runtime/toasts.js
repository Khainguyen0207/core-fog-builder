class ToastManager {
    constructor(container) {
        this.container = container;
        this.toasts = new Map();
        this.nextId = 0;
    }

    show(options = {}) {
        if (!this.container) return null;
        const id = ++this.nextId;
        const element = document.createElement('div');
        element.className = `toast ${options.type || 'info'}`;
        element.setAttribute('role', 'alert');
        element.setAttribute('aria-live', 'assertive');
        const content = document.createElement('div');
        content.className = 'toast-content';
        if (options.title) {
            const title = document.createElement('div');
            title.className = 'toast-title';
            title.textContent = options.title;
            content.append(title);
        }
        const message = document.createElement('div');
        message.className = 'toast-message';
        message.textContent = options.message || '';
        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'toast-close';
        close.setAttribute('aria-label', 'Close');
        close.textContent = 'x';
        close.addEventListener('click', () => this.dismiss(id));
        content.append(message);
        element.append(content, close);
        this.container.append(element);
        this.toasts.set(id, element);
        requestAnimationFrame(() => element.classList.add('show'));
        if (options.duration !== 0 && options.duration !== false) {
            element.sharedTimer = setTimeout(() => this.dismiss(id), options.duration || 5000);
        }
        return id;
    }

    dismiss(id) {
        const element = this.toasts.get(id);
        if (!element) return;
        clearTimeout(element.sharedTimer);
        element.classList.remove('show');
        element.classList.add('hide');
        setTimeout(() => element.remove(), 400);
        this.toasts.delete(id);
    }
}

function initializeToastRoot(root) {
    if (root.dataset.sharedInitialized === 'true') return;
    const container = root.querySelector('[data-shared-toast-container]');
    const config = root.querySelector('[data-shared-toast-config]');
    if (!container || !config) return;
    const manager = new ToastManager(container);
    window.toastManager = manager;
    try {
        JSON.parse(config.textContent || '[]').forEach(message => manager.show(message));
    } catch (error) {
        console.error('Invalid Shared toast configuration.', error);
    }
    root.dataset.sharedInitialized = 'true';
}

export function initializeToasts(scope = document) {
    scope.querySelectorAll('[data-shared-toasts]').forEach(initializeToastRoot);
}
