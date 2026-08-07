
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to {{config('app.name')}}</title>

    <style>
        #toast-section,
        #toast-section * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #toast-section {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
            sans-serif;
            background: linear-gradient(135deg, #6279e0 0%, #704898 100%);
            min-height: 100vh;
            padding: 20px;
        }

        #toast-section .demo-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        #toast-section .demo-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 40px;
        }

        #toast-section .controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        #toast-section .control-group {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        #toast-section .control-group h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.1rem;
        }

        #toast-section .control-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        #toast-section .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            flex: 1;
            min-width: 100px;
        }

        #toast-section .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        #toast-section .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(17, 19, 33, 0.5);
        }

        #toast-section .btn-success {
            background: #10b981;
            color: #fff;
        }
        #toast-section .btn-error {
            background: #ef4444;
            color: #fff;
        }
        #toast-section .btn-warning {
            background: #f59e0b;
            color: #fff;
        }
        #toast-section .btn-info {
            background: #3b82f6;
            color: #fff;
        }
        #toast-section .btn-loading {
            background: #6366f1;
            color: #fff;
        }
        #toast-section .btn-promise {
            background: #8b5cf6;
            color: #fff;
        }

        #toast-section .btn-primary {
            background: #5a67d8;
            color: #f3eeee;
        }

        #toast-section select,
        #toast-section input {
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
            width: 100%;
        }

        #toast-section select:focus,
        #toast-section input:focus {
            outline: none;
            border-color: #667eea;
        }

        
        #toast-section .toast-container {
            position: fixed;
            z-index: 10000;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 400px;
            width: 100%;
        }

        #toast-section .toast-container.top-left {
            top: 20px;
            left: 20px;
        }
        #toast-section .toast-container.top-center {
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
        #toast-section .toast-container.top-right {
            top: 20px;
            right: 20px;
        }
        #toast-section .toast-container.bottom-left {
            bottom: 20px;
            left: 20px;
        }
        #toast-section .toast-container.bottom-center {
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
        #toast-section .toast-container.bottom-right {
            bottom: 20px;
            right: 20px;
        }

        
        #toast-section .toast {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(100px);
            opacity: 0;
            max-width: 100%;
            min-width: 300px;
            position: relative;
            overflow: hidden;
        }

        #toast-section .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        #toast-section .toast.hide {
            transform: translateY(-100px);
            opacity: 0;
            margin-top: -80px;
        }

        
        #toast-section .toast.light {
            background: rgba(255, 255, 255, 0.95);
            color: #374151;
        }

        #toast-section .toast.dark {
            background: rgba(31, 41, 55, 0.95);
            color: #f9fafb;
        }

        #toast-section .toast.success {
            background: linear-gradient(
                135deg,
                rgba(16, 185, 129, 0.9),
                rgba(5, 150, 105, 0.9)
            );
            color: #fff;
        }

        #toast-section .toast.error {
            background: linear-gradient(
                135deg,
                rgba(239, 68, 68, 0.9),
                rgba(220, 38, 38, 0.9)
            );
            color: #fff;
        }

        #toast-section .toast.warning {
            background: linear-gradient(
                135deg,
                rgba(245, 158, 11, 0.9),
                rgba(217, 119, 6, 0.9)
            );
            color: #fff;
        }

        #toast-section .toast.info {
            background: linear-gradient(
                135deg,
                rgba(59, 130, 246, 0.9),
                rgba(37, 99, 235, 0.9)
            );
            color: #fff;
        }

        #toast-section .toast.minimal {
            background: rgba(255, 255, 255, 0.98);
            color: #374151;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #toast-section .toast.glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #1f2937;
        }

        
        #toast-section .toast-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #toast-section .toast-icon svg {
            width: 100%;
            height: 100%;
        }

        
        #toast-section .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: currentColor;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        
        #toast-section .toast-content {
            flex: 1;
        }

        #toast-section .toast-title {
            font-weight: 600;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        #toast-section .toast-message {
            opacity: 0.9;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        
        #toast-section .toast-close {
            background: none;
            border: none;
            color: currentColor;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            opacity: 0.7;
            transition: opacity 0.2s ease;
            flex-shrink: 0;
        }

        #toast-section .toast-close:hover {
            opacity: 1;
        }

        
        #toast-section .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 0 0 12px 12px;
            transition: width linear;
            width: 0;
        }

        
        #toast-section .toast-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        #toast-section .toast-action {
            padding: 6px 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: currentColor;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #toast-section .toast-action:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        
        @media (max-width: 768px) {
            #toast-section .toast-container {
                max-width: calc(100vw - 40px);
                left: 20px !important;
                right: 20px !important;
                transform: none !important;
            }

            #toast-section .toast {
                min-width: auto;
                width: 100%;
            }

            #toast-section .controls {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

@if(auth()->user())
    <a href="{{ route('admin.dashboard.index') }}">Home</a>
@else
    <a href="{{ route('login') }}">Login</a>

@endif
<section id="toast-section" style="display: none">
    <div class="demo-container">
        <h1 class="demo-title">Toast Notifications</h1>
        <div class="controls">
            <div class="control-group">
                <h3>Toast Types</h3>
                <div class="control-row">
                    <button class="btn btn-success"
                            onclick="showToast('success', 'Success!', 'Operation completed successfully')">Success</button>
                    <button class="btn btn-error"
                            onclick="showToast('error', 'Error!', 'Something went wrong')">Error</button>
                </div>
                <div class="control-row">
                    <button class="btn btn-warning"
                            onclick="showToast('warning', 'Warning!', 'Please check your input')">Warning</button>
                    <button class="btn btn-info"
                            onclick="showToast('info', 'Info', 'Here is some information')">Info</button>
                </div>
                <div class="control-row">
                    <button class="btn btn-loading" onclick="showLoadingToast()">Loading</button>
                    <button class="btn btn-promise" onclick="showPromiseToast()">Promise</button>
                </div>
            </div>

            <div class="control-group">
                <h3>Position</h3>
                <select id="positionSelect" onchange="updatePosition()">
                    <option value="top-right">Top Right</option>
                    <option value="top-left">Top Left</option>
                    <option value="top-center">Top Center</option>
                    <option value="bottom-right">Bottom Right</option>
                    <option value="bottom-left">Bottom Left</option>
                    <option value="bottom-center">Bottom Center</option>
                </select>
            </div>

            <div class="control-group">
                <h3>Theme</h3>
                <select id="themeSelect">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="minimal">Minimal</option>
                    <option value="glass">Glass</option>
                </select>
            </div>

            <div class="control-group">
                <h3>Duration (ms)</h3>
                <input type="number" id="durationInput" value="4000" min="1000" max="10000" step="500" />
            </div>

            <div class="control-group">
                <h3>Custom Toast</h3>
                <input type="text" id="customTitle" placeholder="Enter title..." style="margin-bottom: 10px" />
                <input type="text" id="customMessage" placeholder="Enter message..." style="margin-bottom: 10px" />
                <button class="btn btn-primary" onclick="showCustomToast()">Show Custom</button>
            </div>

            <div class="control-group">
                <h3>Actions</h3>
                <div class="control-row">
                    <button class="btn btn-primary" onclick="showActionToast()">With Actions</button>
                    <button class="btn btn-primary" onclick="clearAllToasts()">Clear All</button>
                </div>
            </div>
        </div>
    </div>

    
    <div id="toastContainer" class="toast-container top-right"></div>
</section>

<script>
    const toastRoot = document.getElementById("toast-section");

    class ToastManager {
        constructor() {
            this.toasts = new Map();
            this.container = toastRoot.querySelector("#toastContainer");
            this.toastId = 0;
        }

        show(options = {}) {
            const id = ++this.toastId;
            const toast = this.createToast(id, options);

            this.toasts.set(id, toast);
            this.container.appendChild(toast.element);

            requestAnimationFrame(() => {
                toast.element.classList.add("show");
            });

            if (options.duration !== 0 && options.duration !== false) {
                toast.timer = setTimeout(() => {
                    this.dismiss(id);
                }, options.duration || 4000);

                if (toast.progressBar) {
                    toast.progressBar.style.transitionDuration = `${options.duration || 4000}ms`;
                    requestAnimationFrame(() => {
                        toast.progressBar.style.width = "100%";
                    });
                }
            }

            return id;
        }

        createToast(id, options) {
            const element = document.createElement("div");
            element.className = `toast ${options.theme || "light"} ${options.type || ""}`;
            element.dataset.toastId = id;

            let iconSvg = this.getIcon(options.type, options.loading);

            element.innerHTML = `
          ${iconSvg ? `<div class="toast-icon">${iconSvg}</div>` : ""}
          <div class="toast-content">
            ${options.title ? `<div class="toast-title">${options.title}</div>` : ""}
            ${options.message ? `<div class="toast-message">${options.message}</div>` : ""}
            ${options.actions ? this.createActions(options.actions, id) : ""}
          </div>
          ${options.closable !== false ? `<button class="toast-close" onclick="toastManager.dismiss(${id})">×</button>` : ""}
          ${options.showProgress !== false && options.duration !== 0 ? '<div class="toast-progress"></div>' : ""}
        `;

            const progressBar = element.querySelector(".toast-progress");

            element.addEventListener("mouseenter", () => {
                const toast = this.toasts.get(id);
                if (toast && toast.timer) {
                    clearTimeout(toast.timer);
                }
            });

            element.addEventListener("mouseleave", () => {
                const toast = this.toasts.get(id);
                if (toast && options.duration !== 0) {
                    const remainingTime = options.duration || 4000;
                    toast.timer = setTimeout(() => {
                        this.dismiss(id);
                    }, remainingTime * 0.3);
                }
            });

            return { element, progressBar, timer: null };
        }

        createActions(actions, toastId) {
            return `
          <div class="toast-actions">
            ${actions
                .map(
                    (action) => `
                <button class="toast-action" onclick="${action.onClick || `toastManager.dismiss(${toastId})`}">
                  ${action.label}
                </button>
              `
                )
                .join("")}
          </div>
        `;
        }

        getIcon(type, loading = false) {
            if (loading) {
                return '<div class="spinner"></div>';
            }

            const icons = {
                success:
                    '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                error:
                    '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                warning:
                    '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                info:
                    '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
            };

            return icons[type] || "";
        }

        dismiss(id) {
            const toast = this.toasts.get(id);
            if (!toast) return;

            if (toast.timer) {
                clearTimeout(toast.timer);
            }

            toast.element.classList.add("hide");

            setTimeout(() => {
                if (toast.element.parentNode) {
                    toast.element.parentNode.removeChild(toast.element);
                }
                this.toasts.delete(id);
            }, 400);
        }

        update(id, options) {
            const toast = this.toasts.get(id);
            if (!toast) return;

            const content = toast.element.querySelector(".toast-content");
            if (options.title) {
                const titleEl =
                    content.querySelector(".toast-title") ||
                    document.createElement("div");
                titleEl.className = "toast-title";
                titleEl.textContent = options.title;
                if (!content.querySelector(".toast-title")) {
                    content.insertBefore(titleEl, content.firstChild);
                }
            }

            if (options.message) {
                const messageEl =
                    content.querySelector(".toast-message") ||
                    document.createElement("div");
                messageEl.className = "toast-message";
                messageEl.textContent = options.message;
                if (!content.querySelector(".toast-message")) {
                    content.appendChild(messageEl);
                }
            }

            if (options.type) {
                toast.element.className = `toast show ${options.theme || "light"} ${options.type}`;
                const iconEl = toast.element.querySelector(".toast-icon");
                if (iconEl) {
                    iconEl.innerHTML = this.getIcon(options.type, options.loading);
                }
            }
        }

        clear() {
            this.toasts.forEach((toast, id) => {
                this.dismiss(id);
            });
        }

        promise(promise, options = {}) {
            const loadingId = this.show({
                ...options,
                loading: true,
                duration: 0,
                title: options.loading || "Loading...",
                theme: options.theme || "light",
            });

            promise
                .then((result) => {
                    this.update(loadingId, {
                        type: "success",
                        title: options.success || "Success!",
                        message: result?.message || "Operation completed successfully",
                        loading: false,
                    });

                    setTimeout(() => {
                        this.dismiss(loadingId);
                    }, options.duration || 3000);
                })
                .catch((error) => {
                    this.update(loadingId, {
                        type: "error",
                        title: options.error || "Error!",
                        message: error?.message || "Something went wrong",
                        loading: false,
                    });

                    setTimeout(() => {
                        this.dismiss(loadingId);
                    }, options.duration || 4000);
                });

            return loadingId;
        }

        setPosition(position) {
            this.container.className = `toast-container ${position}`;
        }
    }

    
    const toastManager = new ToastManager();

    function getTheme() {
        return toastRoot.querySelector("#themeSelect").value;
    }

    function getDuration() {
        return parseInt(toastRoot.querySelector("#durationInput").value, 10);
    }

    function showToast(type, title, message) {
        const theme = getTheme();
        const duration = getDuration();

        toastManager.show({
            type,
            title,
            message,
            theme,
            duration,
        });
    }

    function showCustomToast() {
        const title = toastRoot.querySelector("#customTitle").value;
        const message = toastRoot.querySelector("#customMessage").value;
        const theme = getTheme();
        const duration = getDuration();

        if (!title && !message) {
            showToast("warning", "Warning", "Please enter a title or message");
            return;
        }

        toastManager.show({
            title: title || undefined,
            message: message || undefined,
            theme,
            duration,
        });
    }

    function showLoadingToast() {
        const theme = getTheme();

        toastManager.show({
            title: "Loading...",
            message: "Please wait while we process your request",
            loading: true,
            duration: 0,
            theme,
        });
    }

    function showPromiseToast() {
        const theme = getTheme();

        const mockPromise = new Promise((resolve, reject) => {
            setTimeout(() => {
                Math.random() > 0.5
                    ? resolve({ message: "Data loaded successfully!" })
                    : reject({ message: "Failed to load data" });
            }, 3000);
        });

        toastManager.promise(mockPromise, {
            loading: "Loading data...",
            success: "Success!",
            error: "Failed!",
            theme,
        });
    }

    function showActionToast() {
        const theme = getTheme();

        toastManager.show({
            title: "Confirm Action",
            message: "Do you want to proceed with this action?",
            theme,
            duration: 0,
            actions: [
                {
                    label: "Confirm",
                    onClick:
                        "showToast('success', 'Confirmed!', 'Action was completed'); toastManager.dismiss(this.closest('.toast').dataset.toastId);",
                },
                {
                    label: "Cancel",
                    onClick:
                        "toastManager.dismiss(this.closest('.toast').dataset.toastId);",
                },
            ],
        });
    }

    function updatePosition() {
        const position = toastRoot.querySelector("#positionSelect").value;
        toastManager.setPosition(position);
    }

    function clearAllToasts() {
        toastManager.clear();
    }

    setTimeout(() => {
        showToast("info", "Welcome! 👋", "Try out the different toast options above");
    }, 1000);
</script>
</body>
</html>
