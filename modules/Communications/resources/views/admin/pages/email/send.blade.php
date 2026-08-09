@extends('admin.layouts.contentLayout')

@section('title', 'Send Email')

@section('content')
    <div class="send-email-page">
        <div class="card border-0 shadow-sm send-email-hero-card mb-4">
            <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-label-primary">Email Campaign</span>
                    </div>
                    <h4 class="mb-1">Send template to selected customers</h4>
                    <p class="text-muted mb-0">Filter customers in the table, select an email template, then send in bulk via
                        queue.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-7">
                <div class="card border-0">
                    <div class="card-header">
                        <h5 class="mb-1">Customer Selection</h5>
                        <p class="text-muted mb-0">Select one or multiple customers using checkboxes in the DataTable.</p>
                    </div>
                    <div class="card-body p-0 bg-transparent">
                        @include('admin.components.tables.table', ['table' => $table, 'name' => $name])
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card border-0 shadow-sm sticky-top send-email-preview-card" style="top: 1.5rem;">
                    <div class="card-header">
                        <h5 class="mb-1">Template Setup</h5>
                        <p class="text-muted mb-0">Preview the email with sample data before sending.</p>
                    </div>
                    <div class="card-body">
                        <form id="send-email-form">
                            @csrf

                            <div class="mb-3">
                                <label for="template_id" class="form-label">Select Template</label>
                                <select id="template_id" name="template_id" class="form-select" required>
                                    <option value="">-- Choose a template --</option>
                                    @foreach ($templates as $tmpl)
                                        <option value="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="template-preview" class="d-none">
                                <div class="mb-3">
                                    <h6 class="mb-1" id="preview-name"></h6>
                                    <p class="text-muted mb-0 small" id="preview-desc"></p>
                                </div>

                                <div
                                    class="d-flex flex-column flex-start justify-content-between align-items-start gap-2 mb-2">
                                    <h6 class="mb-0 w-100">Content Preview</h6>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Preview size controls">
                                        <button type="button" class="btn btn-outline-primary active"
                                            data-preview-size="desktop" data-preview-width="920" data-preview-height="620">
                                            Desktop 920px
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" data-preview-size="tablet"
                                            data-preview-width="720" data-preview-height="780">
                                            Tablet 720px
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" data-preview-size="mobile"
                                            data-preview-width="390" data-preview-height="844">
                                            Mobile 390px
                                        </button>
                                    </div>
                                </div>

                                <div class="preview-shell preview-desktop mb-4" id="send-preview-shell">
                                    <iframe id="preview-frame" class="template-preview-frame"
                                        title="Template preview"></iframe>
                                </div>
                                <div class="preview-size-hint mb-4" id="send-preview-size-hint">Frame: 920px x 620px</div>

                                <button type="submit" class="btn btn-primary w-100" id="btn-send">
                                    <i class="bx bx-send me-2"></i> Send to Selected Customers
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="send-email-endpoints" data-preview-url="{{ route('admin.send-email.preview', ['id' => '__id__']) }}"
            data-send-url="{{ route('admin.send-email.send') }}"></div>
    </div>
@endsection

@push('pricing-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('template_id');
            const previewDiv = document.getElementById('template-preview');
            const previewName = document.getElementById('preview-name');
            const previewDesc = document.getElementById('preview-desc');
            const previewFrame = document.getElementById('preview-frame');
            const previewShell = document.getElementById('send-preview-shell');
            const previewHint = document.getElementById('send-preview-size-hint');
            const sendForm = document.getElementById('send-email-form');
            const btnSend = document.getElementById('btn-send');
            const sizeButtons = document.querySelectorAll('[data-preview-size]');
            const endpointElement = document.getElementById('send-email-endpoints');
            const previewUrlTemplate = endpointElement?.dataset.previewUrl || '';
            const sendUrl = endpointElement?.dataset.sendUrl || '';

            const sampleVariables = {
                customer_name: 'John Carter',
                customer_email: 'john.carter@example.com',
                customer_phone: '+1 415 555 0147',
                membership_code: 'GOLD-2026-US'
            };

            const escapeRegExp = (value) => value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

            const applySampleVariables = (content) => {
                let rendered = content || '';

                Object.entries(sampleVariables).forEach(([key, value]) => {
                    const pattern = new RegExp('\\{\\{\\s*' + escapeRegExp(key) + '\\s*\\}\\}', 'g');
                    rendered = rendered.replace(pattern, value);
                });

                return rendered;
            };

            const renderPreviewFrame = (html) => {
                const doc = previewFrame.contentDocument || previewFrame.contentWindow.document;
                doc.open();
                doc.write(html || '<p style="padding: 20px;">Template content is empty.</p>');
                doc.close();
            };

            const resetSendButton = () => {
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bx bx-send me-2"></i> Send to Selected Customers';
            };

            const applySize = (button) => {
                const previewSize = button.dataset.previewSize;
                const previewWidth = Number(button.dataset.previewWidth || 920);
                const previewHeight = Number(button.dataset.previewHeight || 620);

                previewShell.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
                previewShell.classList.add(`preview-${previewSize}`);
                previewShell.style.maxWidth = `${previewWidth}px`;

                previewFrame.classList.remove('frame-desktop', 'frame-tablet', 'frame-mobile');
                previewFrame.classList.add(`frame-${previewSize}`);
                previewFrame.style.minHeight = `${previewHeight}px`;

                if (previewHint) {
                    previewHint.textContent = `Frame: ${previewWidth}px x ${previewHeight}px`;
                }
            };

            sizeButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    sizeButtons.forEach((btn) => btn.classList.remove('active'));
                    this.classList.add('active');
                    applySize(this);
                });
            });

            const firstActive = document.querySelector('[data-preview-size].active');
            if (firstActive) {
                applySize(firstActive);
            }

            templateSelect.addEventListener('change', function() {
                const id = this.value;

                if (!id) {
                    previewDiv.classList.add('d-none');
                    return;
                }

                fetch(previewUrlTemplate.replace('__id__', id))
                    .then((response) => response.json())
                    .then((response) => {
                        if (response.error) {
                            throw new Error(response.message || 'Can not load template');
                        }

                        previewName.textContent = response.data.name;
                        previewDesc.textContent = response.data.description || 'No description';

                        renderPreviewFrame(applySampleVariables(response.data.html));
                        previewDiv.classList.remove('d-none');
                    })
                    .catch(() => {
                        previewDiv.classList.add('d-none');

                        Swal.fire({
                            icon: 'error',
                            title: 'Template Error',
                            text: 'Unable to load selected template.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    });
            });

            sendForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const tableContainer = $('.card-datatable');
                // Lấy mảng ID đã chọn từ container của bảng (lưu qua các trang)
                const userIds = tableContainer.data('selected-ids') || [];

                if (userIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Customers Selected',
                        text: 'Please select at least one customer from the table.',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to send this email template to ${userIds.length} customer(s).`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Send it!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    btnSend.disabled = true;
                    btnSend.innerHTML = '<i class="bx bx-loader bx-spin me-2"></i> Sending...';

                    const formData = new FormData(sendForm);
                    formData.append('user_ids', userIds.join(','));

                    fetch(sendUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                Accept: 'application/json'
                            },
                            body: formData
                        })
                        .then((response) => response.json())
                        .then((response) => {
                            resetSendButton();

                            if (response.error) {
                                throw new Error(response.message ||
                                    'Can not queue this campaign');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });

                            tableContainer.find('.row-checkbox:checked').prop('checked', false);
                            tableContainer.find('#select-all-checkbox').prop('checked', false)
                                .prop('indeterminate', false);
                            tableContainer.data('selected-ids', []);

                            tableContainer.find('#select-all-checkbox').trigger('change');
                        })
                        .catch((error) => {
                            resetSendButton();

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Something went wrong.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        });
                });
            });
        });
    </script>
@endpush
