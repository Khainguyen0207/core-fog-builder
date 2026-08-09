<div class="d-flex justify-content-start align-items-center">
    @if ($image ?? null)<div class="avatar-wrapper"><div class="avatar me-2"><img src="{{ $image }}" alt="Avatar" class="rounded-circle"></div></div>@endif
    <div class="d-flex flex-column">
        @if ($name ?? null)<span class="emp_name text-truncate">{{ $name }}</span>@endif
        @if ($subname ?? null)<small class="emp_post text-truncate text-body-secondary">{!! $subname !!}</small>@endif
    </div>
</div>
