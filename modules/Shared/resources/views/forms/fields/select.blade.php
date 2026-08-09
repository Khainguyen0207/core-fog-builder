@php
    $name = $field->getName();
    $multiple = $field->isMultiple();
    $fieldId = \Illuminate\Support\Str::slug(($fieldIdPrefix ?? 'field').'-'.$name);
    $normalize = function ($value) {
        if ($value instanceof \BackedEnum) return $value->value;
        if (is_object($value) && method_exists($value, 'getValue')) return $value->getValue();
        return $value;
    };
    $data = old($name, $data ?? $field->getDefaultValue());
    $selectedValues = array_map(fn ($value) => (string) $normalize($value), $multiple ? (array) ($data ?: $field->getValue()) : [$normalize($data)]);
    $col = match ($field->getSpan()) { 1 => 'col-12 col-sm-6', 2 => 'col-12', default => 'col-12 col-sm' };
@endphp
<div class="{{ $col }} mb-3">
    <label for="{{ $fieldId }}" class="form-label">{{ $field->getLabel() }}</label>
    <select class="selectpicker w-100 @error($name) is-invalid @enderror" data-style="btn-default" data-width="100%" name="{{ $multiple ? $name.'[]' : $name }}" id="{{ $fieldId }}" @if($multiple) multiple @endif @required($field->required) @disabled($field->disabled)>
        @if ($field->isFilter())<option value="">Select {{ $field->getLabel() }}</option>@endif
        @foreach ($field->getOptions() as $option => $label)
            @php($optionValue = (string) $normalize($option))
            <option value="{{ $optionValue }}" @selected(in_array($optionValue, $selectedValues, true))>{{ $label }}</option>
        @endforeach
    </select>
    @if ($helper = $field->getAttribute('helper_text'))<div id="{{ $fieldId }}-help" class="form-text">{!! $helper !!}</div>@endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
