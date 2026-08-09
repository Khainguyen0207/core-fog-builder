import { bootstrap, PerfectScrollbar } from '../globals.js';

function initializeMenu(menu) {
    if (menu.dataset.sharedInitialized === 'true') return;

    const inner = menu.querySelector('.menu-inner');
    if (inner) {
        menu.sharedScrollbar = new PerfectScrollbar(inner, {
            suppressScrollX: true,
            wheelPropagation: false,
        });
        const active = inner.querySelector('.menu-item.active:not(.open)');
        if (active && active.offsetTop > (inner.clientHeight * 2) / 3) {
            inner.scrollTop = active.offsetTop - inner.clientHeight / 2;
        }
    }

    menu.addEventListener('click', event => {
        const toggle = event.target.closest('.menu-toggle');
        if (!toggle || !menu.contains(toggle)) return;
        event.preventDefault();
        const item = toggle.closest('.menu-item');
        if (!item) return;
        const opening = !item.classList.contains('open');
        if (opening) {
            item.parentElement?.querySelectorAll(':scope > .menu-item.open').forEach(sibling => {
                if (sibling !== item) sibling.classList.remove('open');
            });
        }
        item.classList.toggle('open', opening);
        menu.sharedScrollbar?.update();
    });
    menu.dataset.sharedInitialized = 'true';
}

function initializePasswordToggle(wrapper) {
    if (wrapper.dataset.sharedPasswordInitialized === 'true') return;
    const toggle = wrapper.querySelector('[data-shared-password-toggle]');
    const icon = toggle?.querySelector('i');
    const input = wrapper.querySelector('input');
    if (!toggle || !icon || !input) return;
    toggle.addEventListener('click', event => {
        event.preventDefault();
        const reveal = input.type === 'password';
        input.type = reveal ? 'text' : 'password';
        icon.classList.toggle('bx-show', reveal);
        icon.classList.toggle('bx-hide', !reveal);
        toggle.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
    });
    wrapper.dataset.sharedPasswordInitialized = 'true';
}

export function initializeLayout(scope = document) {
    scope.querySelectorAll('[data-shared-menu]').forEach(initializeMenu);
    scope.querySelectorAll('[data-shared-menu-toggle]:not([data-shared-initialized="true"])').forEach(toggle => {
        toggle.addEventListener('click', event => {
            event.preventDefault();
            document.documentElement.classList.toggle('layout-menu-expanded');
        });
        toggle.dataset.sharedInitialized = 'true';
    });
    scope.querySelectorAll('[data-shared-form] .form-password-toggle').forEach(initializePasswordToggle);
    scope.querySelectorAll('[data-bs-toggle="tooltip"]:not([data-shared-tooltip-initialized="true"])').forEach(element => {
        bootstrap.Tooltip.getOrCreateInstance(element);
        element.dataset.sharedTooltipInitialized = 'true';
    });
}
