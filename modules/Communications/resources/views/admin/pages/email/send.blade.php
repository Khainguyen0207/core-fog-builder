@extends('shared::layouts.content')

@section('title', 'Send Email')

@section('content')
    <div class="send-email-page" data-communications-send-email
        data-preview-url="{{ route('admin.send-email.preview', ['id' => '__id__']) }}"
        data-send-url="{{ route('admin.send-email.send') }}">
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
                        @include('shared::tables.table', ['table' => $table, 'name' => $name])
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

    </div>
@endsection

@push('scripts')
    @vite('modules/Communications/resources/js/email-template-preview.js')
@endpush

@push('styles')
    @vite('modules/Communications/resources/scss/email-template-preview.scss')
@endpush
