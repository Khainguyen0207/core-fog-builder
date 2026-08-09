<div class="col">
    <div class="card h-100 bg-white"><div class="card-header d-flex flex-column justify-content-between align-items-start h-100">
        <h5 class="mb-0">{{ $panel->getName() }}</h5>
        <h6 class="text-secondary">{{ $panel->getDescription() }}</h6>
        <a href="{{ $panel->getUrl() }}" class="btn btn-label-primary"><span class="icon-base {{ $panel->getIcon() }} me-1"></span>{{ $panel->getButtonLabel() }}</a>
    </div></div>
</div>
