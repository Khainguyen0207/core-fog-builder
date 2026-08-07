@php
    $name = $field->getName();
    $label = $field->getLabel();
    $span = $field->getSpan();
    $type = $field->getType() ?? 'default';
    $class = $field->getAttribute('class') ?? '';
    $placeholder = $field->getAttribute('placeholder');
    $col = match ($span) {
        1 => 'col-6',
        2 => 'col-12',
        default => 'col',
    };
@endphp

@if ($type === 'textarea')
    <div class="col {{ $col }} mb-3">
        @if ($label)
            <p class="mb-1">{{ $label }}</p>
        @endif

        <textarea name="{{ $name }}" id="{{ $name }}"
            class="{{ $class }} 
            @if (old($name) !== null && !$errors->has($name)) is-valid @endif
            @error($name) is-invalid @enderror
            rounded-bottom-2 rounded-top-0">{!! old($name) ?? $data !!}</textarea>
    </div>
@else
    <div class="col {{ $col }} mb-3">
        @if ($label)
            <p class="mb-1">{{ $label }}</p>
        @endif

        <div id="full-editor" class="{{ $class }}  rounded-bottom-2 rounded-top-0">{!! old($name) ?? $data !!}</div>
        <input type="hidden" name="{{ $name }}" id="input-hidden-full-editor">
    </div>
@endif

@if (count($errors) === 0 || !$errors->get($name))
    <div class="invalid-feedback">{{ $field->getLabel() }} is required</div>
@else
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }} </div>
    @enderror
@endif
