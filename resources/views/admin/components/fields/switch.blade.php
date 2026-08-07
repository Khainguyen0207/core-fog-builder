@php
    $name = $field->getName();
    $label = $field->getLabel();
    $span = $field->getSpan();

    $col = match ($span) {
        1 => 'col-6',
        2 => 'col-12',
        default => 'col',
    };

    if ($data === '' || $data === null) {
        if (old($name) !== null) {
            $data = old($name);
        } else {
            $data = $field->getDefaultValue();
        }
    }

    $checked = filter_var($data, FILTER_VALIDATE_BOOLEAN);
@endphp

<div class="{{ $col }} mb-3">
    <div class="">
        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
        <div class="form-check form-switch">
            <input type="hidden" name="{{ $name }}" value="0">
            <input class="form-check-input" style="width: 3rem; height: 1.5rem;" type="checkbox" role="switch"
                id="{{ $name }}" name="{{ $name }}" value="1" {{ $checked ? 'checked' : '' }}
                {{ $field->disabled ? 'disabled' : '' }}>
        </div>
    </div>

    @if (count($errors) === 0 || !$errors->get($name))
        <div class="invalid-feedback">{{ $field->getLabel() }} is required</div>
    @else
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    @endif
</div>
