@php
    $name = $field->getName();
    $fieldId = \Illuminate\Support\Str::slug(($fieldIdPrefix ?? 'field').'-'.$name);
    $data = old($name, $data ?? $field->getDefaultValue());
    $checked = filter_var($data, FILTER_VALIDATE_BOOLEAN);
    $col = match ($field->getSpan()) { 1 => 'col-12 col-sm-6', 2 => 'col-12', default => 'col-12 col-sm' };
@endphp
<div class="{{ $col }} mb-3">
    <label class="form-label" for="{{ $fieldId }}">{{ $field->getLabel() }}</label>
    <div class="form-check form-switch">
        @unless($field->disabled)<input type="hidden" name="{{ $name }}" value="0">@endunless
        <input class="form-check-input @error($name) is-invalid @enderror" style="width:3rem;height:1.5rem" type="checkbox" role="switch" id="{{ $fieldId }}" name="{{ $name }}" value="1" @checked($checked) @required($field->required) @disabled($field->disabled) @readonly($field->readonly)>
    </div>
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
