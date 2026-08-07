@php
    use Illuminate\Support\Arr;

    $attributes = $action->getAttributes();
    $dataActionUrl = $action->getDataActionUrl();
    $actionUrl = $action->getActionUrl();
    $icon = $action->getIcon();
    $method = $action->getMethod();
    $description = !empty($action->getDescription()) ? $action->getDescription() : null;
    $nameViewModal = !empty($action->getNameViewModal()) ? $action->getNameViewModal() : '#confirm-modal' ;
    $viewModal = !empty($action->getViewModal()) ? $action->getViewModal() : null ;
@endphp

<a href="{{ !empty($actionUrl) ? route($actionUrl) : '#' }}"
   class="{{ Arr::get( $attributes, 'class') }}"
   @foreach($attributes as $key => $value)
       @if($key === 'class')
           @continue
       @endif
       {{ $key .'='."$value" }}
   @endforeach
   @if($dataActionUrl)
       data-bs-action="{{route($dataActionUrl)}}"
        @endif
>
    <span class="icon-base {{ $icon }} icon-sm me-2"></span>
    {{ $action->getLabel() }}


</a>




