@extends('admin.layouts.contentLayout')

@section('title', 'Template Preview')

@section('content')
    <div class="email-preview-page">
        <div class="card border-0 shadow-sm preview-hero-card mb-4">
            <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge bg-label-primary">Email Template Preview</span>
                    </div>
                    <h4 class="mb-1">{{ $emailTemplate->name }}</h4>
                    <p class="text-muted mb-0">{{ $emailTemplate->description ?: 'No description is available for this template.' }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back to Templates
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm preview-render-card h-100">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h6 class="mb-0">Rendered Email</h6>
                            <small class="text-muted">Live rendering simulation in the browser.</small>
                        </div>
                        <div class="btn-group" role="group" aria-label="Preview size controls">
                            <button type="button" class="btn btn-outline-primary active" data-preview-size="desktop"
                                data-preview-width="1200" data-preview-height="760">
                                Desktop 1200px
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-preview-size="tablet"
                                data-preview-width="834" data-preview-height="900">
                                Tablet 834px
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-preview-size="mobile"
                                data-preview-width="390" data-preview-height="844">
                                Mobile 390px
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="preview-shell preview-desktop" id="preview-shell">
                            <iframe id="template-preview-frame" class="template-preview-frame"
                                title="Template preview"></iframe>
                        </div>
                        <div class="preview-size-hint" id="preview-size-hint">Frame: 1200px x 760px</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm variable-preview-card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">Sample Variables</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">This template is currently rendered with sample data:</p>
                        <div class="variable-list">
                            @foreach ($variables as $key => $value)
                                <div class="variable-item">
                                    <code>{!! $key !!}</code>
                                    <span>{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="preview-html-data" data-html="{{ rawurlencode($html ?? '') }}"></div>
    </div>
@endsection

@push('pricing-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const htmlData = document.getElementById('preview-html-data');
            const html = decodeURIComponent(htmlData?.dataset.html || '');
            const frame = document.getElementById('template-preview-frame');
            const shell = document.getElementById('preview-shell');
            const hint = document.getElementById('preview-size-hint');
            const sizeButtons = document.querySelectorAll('[data-preview-size]');

            const doc = frame.contentDocument || frame.contentWindow.document;
            doc.open();
            doc.write(html || '<p style="padding:20px">Template content is empty.</p>');
            doc.close();

            const applySize = (button) => {
                const previewSize = button.dataset.previewSize;
                const previewWidth = Number(button.dataset.previewWidth || 1200);
                const previewHeight = Number(button.dataset.previewHeight || 760);

                shell.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
                shell.classList.add(`preview-${previewSize}`);
                shell.style.maxWidth = `${previewWidth}px`;

                frame.classList.remove('frame-desktop', 'frame-tablet', 'frame-mobile');
                frame.classList.add(`frame-${previewSize}`);
                frame.style.minHeight = `${previewHeight}px`;

                if (hint) {
                    hint.textContent = `Frame: ${previewWidth}px x ${previewHeight}px`;
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
        });
    </script>
@endpush
