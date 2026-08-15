import { expect, test } from '@playwright/test';

test('calendar initializes Flatpickr and remains responsive after an admin signs in', async ({ page }) => {
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));

    await page.goto('http://localhost:8000/login');
    await page.locator('#email').fill('admin@admin.vn');
    await page.locator('#password').fill('admin@admin.vn');
    await page.getByRole('button', { name: 'Login' }).click();
    await page.waitForURL('**/admin/dashboard');

    await page.goto('http://localhost:8000/admin/calendar');
    await expect(page.locator('#calendar .fc-view-harness')).toBeVisible();
    await expect(page.locator('.flatpickr-calendar.inline')).toBeVisible();
    await expect.poll(() => page.locator('#calendarDate').evaluate(element => Boolean(element._flatpickr))).toBe(true);

    const layout = await page.evaluate(() => {
        const sidebar = document.querySelector('.app-calendar-sidebar').getBoundingClientRect();
        const content = document.querySelector('.app-calendar-content').getBoundingClientRect();

        return { sidebar, content };
    });
    expect(layout.sidebar.top).toBe(layout.content.top);
    expect(layout.sidebar.width).toBeLessThan(layout.content.width);

    const dateInput = page.locator('#calendarDate');
    const initialValue = await dateInput.inputValue();
    await page.locator('.flatpickr-calendar .flatpickr-day:not(.prevMonthDay):not(.nextMonthDay):not(.disabled)').nth(14).click();
    await expect(dateInput).not.toHaveValue(initialValue);

    await page.setViewportSize({ width: 390, height: 844 });
    await expect(page.locator('.fc-list')).toBeVisible();
    const mobileLayout = await page.evaluate(() => {
        const sidebar = document.querySelector('.app-calendar-sidebar').getBoundingClientRect();
        const content = document.querySelector('.app-calendar-content').getBoundingClientRect();

        return { sidebar, content };
    });
    expect(mobileLayout.sidebar.width).toBe(mobileLayout.content.width);
    expect(mobileLayout.sidebar.top).toBeLessThan(mobileLayout.content.top);
    expect(errors).toEqual([]);
});

test('shared controls and tables remain usable on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('http://localhost:8000/login');
    await page.locator('#email').fill('admin@admin.vn');
    await page.locator('#password').fill('admin@admin.vn');
    await page.getByRole('button', { name: 'Login' }).click();
    await page.waitForURL('**/admin/dashboard');

    await page.goto('http://localhost:8000/admin/users');
    await expect.poll(() => page.evaluate(() => document.documentElement.scrollWidth)).toBe(390);
    const sortedUsersResponse = page.waitForResponse(response =>
        response.url().includes('/admin/get-data/users') && response.request().method() === 'POST'
    );
    await page.locator('.dt-scroll-head thead th').filter({ hasText: 'Name' }).click();
    expect((await sortedUsersResponse).status()).toBe(200);
    const filterSelect = page.locator('.bootstrap-select .dropdown-toggle').first();
    await expect(filterSelect).toBeVisible();
    await filterSelect.click();
    await expect(page.locator('.bootstrap-select .dropdown-menu.show').first()).toBeVisible();

    await page.goto('http://localhost:8000/admin/services/create');
    await expect(page.locator('.ql-toolbar')).toBeVisible();
    await expect(page.locator('.bootstrap-select .dropdown-toggle').first()).toBeVisible();
    const editorLayout = await page.evaluate(() => {
        const editor = document.querySelector('[data-shared-editor]');
        const nextField = editor.nextElementSibling;

        return {
            editorBottom: editor.getBoundingClientRect().bottom,
            nextFieldTop: nextField.getBoundingClientRect().top,
        };
    });
    expect(editorLayout.editorBottom).toBeLessThanOrEqual(editorLayout.nextFieldTop);
    await expect.poll(() => page.evaluate(() => document.documentElement.scrollWidth)).toBe(390);

    await page.goto('http://localhost:8000/admin/users/create');
    const passwordToggle = page.locator('[data-shared-password-toggle]').first();
    const password = page.locator(`#${await passwordToggle.getAttribute('aria-controls')}`);
    await passwordToggle.click();
    await expect(password).toHaveAttribute('type', 'text');

    await page.locator('[data-shared-menu-toggle]').first().click();
    await expect(page.locator('html')).toHaveClass(/layout-menu-expanded/);
    await expect(page.locator('.app-brand-logo img')).toBeVisible();
    await expect(page.locator('.app-brand-text')).toHaveCount(0);
});

test('membership settings table actions and controls remain visually distinct', async ({ page }) => {
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));

    await page.goto('http://localhost:8000/login');
    await page.locator('#email').fill('admin@admin.vn');
    await page.locator('#password').fill('admin@admin.vn');
    await page.getByRole('button', { name: 'Login' }).click();
    await page.waitForURL('**/admin/dashboard');
    await page.goto('http://localhost:8000/admin/membership-settings');

    await expect(page.getByRole('link', { name: 'Create' })).toBeVisible();
    const styles = await page.evaluate(() => {
        const table = document.querySelector('[data-shared-table]');
        const length = document.querySelector('.dt-length');
        const reload = [...document.querySelectorAll('a')].find(link => link.textContent.includes('Reload'));
        const select = document.querySelector('.bootstrap-select > .dropdown-toggle');
        const tableHeader = document.querySelector('.dt-scroll-head thead th');
        const orderIndicator = document.querySelector('.dt-scroll-head .dt-column-order');

        return {
            tableHasPaddingClass: table.classList.contains('pb-4'),
            lengthMarginBottom: getComputedStyle(length).marginBottom,
            reloadBorderWidth: getComputedStyle(reload).borderTopWidth,
            selectBackground: getComputedStyle(select).backgroundColor,
            selectBorder: getComputedStyle(select).borderColor,
            tableHeaderBackground: getComputedStyle(tableHeader).backgroundColor,
            tableHeaderFontWeight: getComputedStyle(tableHeader).fontWeight,
            tableHeaderOrderIconBefore: getComputedStyle(orderIndicator, '::before').content,
            tableHeaderOrderIconAfter: getComputedStyle(orderIndicator, '::after').content,
        };
    });

    expect(styles).toEqual({
        tableHasPaddingClass: true,
        lengthMarginBottom: '12px',
        reloadBorderWidth: '1px',
        selectBackground: 'rgb(255, 255, 255)',
        selectBorder: 'rgb(206, 209, 213)',
        tableHeaderBackground: 'rgba(133, 146, 163, 0.08)',
        tableHeaderFontWeight: '600',
        tableHeaderOrderIconBefore: '"➜"',
        tableHeaderOrderIconAfter: '"➜"',
    });

    const sortableHeader = page.locator('.dt-scroll-head thead th.dt-orderable-asc').first();
    await sortableHeader.hover();
    await expect.poll(() => sortableHeader.evaluate(element => getComputedStyle(element).outlineStyle)).toBe('none');

    await page.getByRole('link', { name: 'Create' }).click();
    await expect(page.getByPlaceholder('Enter membership code...')).toBeVisible();

    await page.setViewportSize({ width: 390, height: 844 });
    await expect.poll(() => page.evaluate(() => document.documentElement.scrollWidth)).toBe(390);
    expect(errors).toEqual([]);
});
