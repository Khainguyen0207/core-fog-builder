import { expect, test } from '@playwright/test';

test('calendar initializes Flatpickr after an admin signs in', async ({ page }) => {
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
    const mobileLayout = await page.evaluate(() => {
        const sidebar = document.querySelector('.app-calendar-sidebar').getBoundingClientRect();
        const content = document.querySelector('.app-calendar-content').getBoundingClientRect();

        return { sidebar, content };
    });
    expect(mobileLayout.sidebar.width).toBe(mobileLayout.content.width);
    expect(mobileLayout.sidebar.top).toBeLessThan(mobileLayout.content.top);
    expect(errors).toEqual([]);
});
