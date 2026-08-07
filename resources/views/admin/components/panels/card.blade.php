@php
    $title = $panel->getName();
    $subtitle = $panel->getDescription();
    $url = $panel->getUrl();
    $btnLabel = $panel->getButtonLabel();
@endphp
<div class="col ">
    <div class="card h-100 bg-white">
        <div class="card-header d-flex flex-column justify-content-between  align-items-start h-100">
            <h5 class="mb-0">{{ $title }}</h5>
            <h6 class="text-secondary">{{ $subtitle }}</h6>

            <a href="{{ $url }}" class="btn btn-label-primary">
                <span class="icon-base {{ $panel->getIcon() }} me-1"></span>
                {{ $btnLabel }}
            </a>
        </div>
    </div>
</div>
