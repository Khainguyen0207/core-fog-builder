@php
    $attributes = $operation->getAttributes();
    $actionRoute = $operation->getActionUrl();
    $dataActionRoute = $operation->getDataActionUrl();
    $modal = $operation->getNameViewModal() ?: '#confirm-modal-'.\Illuminate\Support\Str::slug($tableName ?? 'table');
@endphp
<a href="{{ $actionRoute && \Illuminate\Support\Facades\Route::has($actionRoute) ? route($actionRoute, $id) : '#' }}"
    @if($dataActionRoute && \Illuminate\Support\Facades\Route::has($dataActionRoute)) data-bs-action="{{ route($dataActionRoute, $id) }}" @endif
    data-shared-operation data-bs-key="{{ $id }}" data-bs-datakey="{{ $attributes['key'] ?? '' }}" data-bs-method="{{ $operation->getMethod() }}"
    class="{{ $attributes['class'] ?? '' }}" @if($operation->isHasModal()) data-bs-toggle="modal" data-bs-target="{{ $modal }}" @endif
    data-bs-content="{{ $operation->getDescription() }}"><span class="icon-base {{ $operation->getIcon() }} icon-sm"></span></a>
