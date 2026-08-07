
class ToastManager {

    constructor() {
        this.toasts = new Map();
        this.container = document.querySelector('#toast-container');
        if (!this.container) {
        }
        this.toastId = 0;
    }


    ensureContainer() {
        if (!this.container) {
            this.container = document.querySelector('#toast-container');
            if (!this.container) {
                console.warn('Toast container not found!');
            }
        }
        return this.container;
    }

    show(options = {}) {
        if (!this.ensureContainer()) return;

        const id = ++this.toastId;
        const toast = this.createToast(id, options);

        this.toasts.set(id, toast);
        this.container.appendChild(toast.element);

        
        requestAnimationFrame(() => {
            toast.element.classList.add("show");
        });

        
        if (options.duration !== 0 && options.duration !== false) {
            const duration = options.duration || 5000;
            toast.timer = setTimeout(() => {
                this.dismiss(id);
            }, duration);

            if (toast.progressBar) {
                toast.progressBar.style.transitionDuration = `${duration}ms`;
                
                void toast.progressBar.offsetWidth;
                requestAnimationFrame(() => {
                    toast.progressBar.style.width = "0%";
                    toast.progressBar.style.width = "100%";
                });
            }
        }

        return id;
    }

    createToast(id, options) {
        const element = document.createElement("div");
        element.className = `toast ${options.type || "info"}`;
        element.dataset.toastId = id;
        element.setAttribute("role", "alert");
        element.setAttribute("aria-live", "assertive");
        element.setAttribute("aria-atomic", "true");

        let iconSvg = this.getIcon(options.type);

        element.innerHTML = `
            ${iconSvg ? `<div class="toast-icon">${iconSvg}</div>` : ""}
            <div class="toast-content">
                ${options.title ? `<div class="toast-title">${options.title}</div>` : ""}
                ${options.message ? `<div class="toast-message">${options.message}</div>` : ""}
            </div>
            <button class="toast-close" onclick="window.toastManager.dismiss(${id})" aria-label="Close">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 1L1 13M1 1l12 12"></path>
                </svg>
            </button>
            <div class="toast-progress"></div>
        `;

        const progressBar = element.querySelector(".toast-progress");

        
        element.addEventListener("mouseenter", () => {
            const toast = this.toasts.get(id);
            if (toast && toast.timer) {
                clearTimeout(toast.timer);
                if (toast.progressBar) {
                    const computedStyle = window.getComputedStyle(toast.progressBar);
                    const width = computedStyle.getPropertyValue('width');
                    toast.progressBar.style.transition = 'none';
                    toast.progressBar.style.width = width;
                }
            }
        });

        element.addEventListener("mouseleave", () => {
            const toast = this.toasts.get(id);
            if (toast && options.duration !== 0) {
                toast.timer = setTimeout(() => {
                    this.dismiss(id);
                }, 2000);

                if (toast.progressBar) {
                    toast.progressBar.style.transition = 'width 2s linear';
                    requestAnimationFrame(() => {
                        toast.progressBar.style.width = "100%";
                    });
                }
            }
        });

        return { element, progressBar, timer: null };
    }

    getIcon(type) {
        const icons = {
            success: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            error: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            warning: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            info: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
        };
        return icons[type] || icons.info;
    }

    dismiss(id) {
        const toast = this.toasts.get(id);
        if (!toast) return;

        if (toast.timer) clearTimeout(toast.timer);

        toast.element.classList.remove("show");
        toast.element.classList.add("hide");

        setTimeout(() => {
            if (toast.element.parentNode) {
                toast.element.parentNode.removeChild(toast.element);
            }
            this.toasts.delete(id);
        }, 400);
    }
}

window.toastManager = new ToastManager();
