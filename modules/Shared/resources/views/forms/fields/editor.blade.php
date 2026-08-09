@php
    $name = $field->getName();
    $fieldId = \Illuminate\Support\Str::slug(($fieldIdPrefix ?? 'field').'-'.$name);
    $value = old($name, $data ?? $field->getDefaultValue());
    $col = match ($field->getSpan()) { 1 => 'col-12 col-sm-6', 2 => 'col-12', default => 'col-12 col-sm' };
@endphp
<div class="{{ $col }} mb-3" @if($field->getType() !== 'textarea') data-shared-editor data-shared-editor-placeholder="{{ $field->getAttribute('placeholder') ?: 'Type Something...' }}" @endif>
    @if ($field->getLabel())<label for="{{ $fieldId }}" class="form-label">{{ $field->getLabel() }}</label>@endif
    @if ($field->getType() === 'textarea')
        <textarea name="{{ $name }}" id="{{ $fieldId }}" class="{{ $field->getAttribute('class') }} @error($name) is-invalid @enderror" placeholder="{{ $field->getAttribute('placeholder') }}" @required($field->required) @disabled($field->disabled) @readonly($field->readonly)>{{ $value }}</textarea>
    @else
        <div class="{{ $field->getAttribute('class') }} rounded-bottom-2 rounded-top-0" data-shared-editor-surface>{!! $value !!}</div>
        <input type="hidden" name="{{ $name }}" id="{{ $fieldId }}" value="{{ $value }}" data-shared-editor-input @disabled($field->disabled)>
    @endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
