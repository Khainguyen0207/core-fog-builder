<div class="col">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ $title }}</h5>
            <h6 class="text-secondary">{{ $subtitle }}</h6>
@dd($url)
            <a href="{{ $url }}" class="btn btn-label-primary">
                <span class="icon-base bx bx-alarm me-1"></span>
                {{ $btnLabel }}
            </a>
        </div>
    </div>
</div>
