@php
    use Illuminate\Support\Arr;

    $attributes = $operation->getAttributes();
    $dataActionUrl = $operation->getDataActionUrl();
    $actionUrl = $operation->getActionUrl();
    $icon = $operation->getIcon();
    $method = $operation->getMethod();
    $description = !empty($operation->getDescription()) ? $operation->getDescription() : null;
    $nameViewModal = !empty($operation->getNameViewModal()) ? $operation->getNameViewModal() : '#confirm-modal' ;
    $viewModal = !empty($operation->getViewModal()) ? $operation->getViewModal() : null ;

@endphp

<a href="{{ !empty($actionUrl) ? route($actionUrl, $id) : '#' }}"
   @if($dataActionUrl)
       data-bs-action="{{ route($dataActionUrl, $id) }}"
   @endif
   data-bs-key="{{ $id }}"
   data-bs-datakey="{{ Arr::get($attributes, 'key') }}"
   data-bs-method="{{ $method }}"
   class="{{ Arr::get($attributes, 'class') }}"
   @if($operation->isHasModal())
       data-bs-toggle="modal"
   data-bs-target="{{ $nameViewModal ?? '#confirm-modal' }}"
   @endif
   data-bs-content="{{ $description }}"
>
    <span class="icon-base {{ $icon }} icon-sm"></span>
</a>









