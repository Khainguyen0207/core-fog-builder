@php
    $attributes = $action->getAttributes();
    $actionRoute = $action->getActionUrl();
    $dataActionRoute = $action->getDataActionUrl();
@endphp
<a href="{{ $actionRoute && \Illuminate\Support\Facades\Route::has($actionRoute) ? route($actionRoute) : '#' }}"
    class="{{ $attributes['class'] ?? '' }}"
    @foreach($attributes as $key => $value) @if($key !== 'class') {{ $key }}="{{ $value }}" @endif @endforeach
    @if($dataActionRoute && \Illuminate\Support\Facades\Route::has($dataActionRoute)) data-bs-action="{{ route($dataActionRoute) }}" @endif>
    <span class="icon-base {{ $action->getIcon() }} icon-sm me-2"></span>{{ $action->getLabel() }}
</a>
