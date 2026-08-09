function writeFrame(frame, html) {
    const frameDocument = frame.contentDocument || frame.contentWindow?.document;
    if (!frameDocument) return;
    frameDocument.open();
    frameDocument.write(html || '<p style="padding:20px">Template content is empty.</p>');
    frameDocument.close();
}

function initializeSizes(root, defaultWidth, defaultHeight) {
    const shell = root.querySelector('.preview-shell');
    const frame = root.querySelector('.template-preview-frame');
    const hint = root.querySelector('.preview-size-hint');
    const buttons = root.querySelectorAll('[data-preview-size]');
    if (!shell || !frame) return;
    const apply = button => {
        const size = button.dataset.previewSize;
        const width = Number(button.dataset.previewWidth || defaultWidth);
        const height = Number(button.dataset.previewHeight || defaultHeight);
        shell.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
        shell.classList.add(`preview-${size}`);
        shell.style.maxWidth = `${width}px`;
        frame.classList.remove('frame-desktop', 'frame-tablet', 'frame-mobile');
        frame.classList.add(`frame-${size}`);
        frame.style.minHeight = `${height}px`;
        if (hint) hint.textContent = `Frame: ${width}px x ${height}px`;
    };
    buttons.forEach(button => button.addEventListener('click', () => {
        buttons.forEach(item => item.classList.remove('active'));
        button.classList.add('active');
        apply(button);
    }));
    const active = root.querySelector('[data-preview-size].active');
    if (active) apply(active);
}

function initializePreview(root) {
    const frame = root.querySelector('.template-preview-frame');
    const data = root.querySelector('#preview-html-data');
    if (!frame || !data) return;
    writeFrame(frame, decodeURIComponent(data.dataset.html || ''));
    initializeSizes(root, 1200, 760);
}

function initializeSend(root) {
    const form = root.querySelector('#send-email-form');
    const select = root.querySelector('#template_id');
    const preview = root.querySelector('#template-preview');
    const frame = root.querySelector('.template-preview-frame');
    const sendButton = root.querySelector('#btn-send');
    if (!form || !select || !preview || !frame || !sendButton) return;
    initializeSizes(root, 920, 620);
    const samples = {
        customer_name: 'John Carter',
        customer_email: 'john.carter@example.com',
        customer_phone: '+1 415 555 0147',
        membership_code: 'GOLD-2026-US',
    };
    const interpolate = content => Object.entries(samples).reduce((html, [key, value]) => {
        const escapedKey = key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return html.replace(new RegExp(`\\{\\{\\s*${escapedKey}\\s*\\}\\}`, 'g'), value);
    }, content || '');
    select.addEventListener('change', async () => {
        if (!select.value) return preview.classList.add('d-none');
        try {
            const response = await fetch(root.dataset.previewUrl.replace('__id__', select.value));
            const payload = await response.json();
            if (payload.error) throw new Error(payload.message);
            root.querySelector('#preview-name').textContent = payload.data.name;
            root.querySelector('#preview-desc').textContent = payload.data.description || 'No description';
            writeFrame(frame, interpolate(payload.data.html));
            preview.classList.remove('d-none');
        } catch {
            preview.classList.add('d-none');
            window.Swal.fire({ icon: 'error', title: 'Template Error', text: 'Unable to load selected template.' });
        }
    });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const table = root.querySelector('[data-shared-table]');
        const userIds = table?.sharedSelectedIds || [];
        if (!userIds.length) {
            await window.Swal.fire({ icon: 'warning', title: 'No Customers Selected', text: 'Please select at least one customer from the table.' });
            return;
        }
        const confirmation = await window.Swal.fire({
            title: 'Are you sure?',
            text: `You are about to send this email template to ${userIds.length} customer(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send it!',
        });
        if (!confirmation.isConfirmed) return;
        sendButton.disabled = true;
        try {
            const formData = new FormData(form);
            formData.append('user_ids', userIds.join(','));
            const response = await fetch(root.dataset.sendUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    Accept: 'application/json',
                },
                body: formData,
            });
            const payload = await response.json();
            if (payload.error) throw new Error(payload.message);
            await window.Swal.fire({ icon: 'success', title: 'Success!', text: payload.message });
            table.querySelectorAll('[data-shared-row-checkbox]:checked').forEach(input => {
                input.checked = false;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        } catch (error) {
            await window.Swal.fire({ icon: 'error', title: 'Error', text: error.message || 'Something went wrong.' });
        } finally {
            sendButton.disabled = false;
        }
    });
}

document.querySelectorAll('[data-communications-email-preview]').forEach(initializePreview);
document.querySelectorAll('[data-communications-send-email]').forEach(initializeSend);
