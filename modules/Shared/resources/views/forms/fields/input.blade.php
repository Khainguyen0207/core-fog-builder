@php
    $name = $field->getName();
    $type = $field->getType();
    $span = $field->getSpan();
    $fieldId = \Illuminate\Support\Str::slug(($fieldIdPrefix ?? 'field').'-'.$name);
    $data = $data ?? null;
    if ($data === '' || $data === null) $data = $field->getDefaultValue();
    $value = old($name, $data);
    $col = match ($span) { 1 => 'col-12 col-sm-6', 2 => 'col-12', default => 'col-12 col-sm' };
@endphp
<div class="{{ $col }} mb-3 {{ $type === 'password' ? 'form-password-toggle' : '' }}" @if($type === 'file' && $field->preview) data-shared-file-preview @endif>
    <label for="{{ $fieldId }}" class="form-label">{{ $field->getLabel() }}</label>
    @if ($type === 'file')
        @if ($field->preview)<div class="file-upload-preview mb-3 text-center"><img src="{{ $data ? \Illuminate\Support\Facades\Storage::url($data) : 'https://placehold.co/150x150' }}" alt="Preview" class="img-fluid rounded-circle" data-shared-file-image style="width:150px;height:150px;object-fit:cover"></div>@endif
        <input type="file" class="form-control @error($name) is-invalid @enderror" id="{{ $fieldId }}" name="{{ $field->multiple ? $name.'[]' : $name }}" accept="{{ $field->getAccept() }}" data-shared-file-input @required($field->required && !$data) @disabled($field->disabled) @if($field->multiple) multiple @endif>
        @if ($field->preview)<button type="button" class="btn btn-outline-secondary mt-2" data-shared-file-remove>Remove file</button>@endif
    @elseif ($type === 'password')
        <div class="input-group input-group-merge">
            <input type="password" class="form-control @error($name) is-invalid @enderror" id="{{ $fieldId }}" name="{{ $name }}" placeholder="{{ $field->getAttribute('placeholder') }}" autocomplete="{{ $field->getAttribute('autocomplete') }}" @required($field->required) @disabled($field->disabled) @readonly($field->readonly)>
            <button type="button" class="input-group-text cursor-pointer" data-shared-password-toggle aria-label="Show password" aria-controls="{{ $fieldId }}"><i class="bx bx-hide"></i></button>
        </div>
    @else
        <input type="{{ $type }}" class="{{ $field->getAttribute('class') }} @error($name) is-invalid @enderror" id="{{ $fieldId }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : $value }}" placeholder="{{ $field->getAttribute('placeholder') }}" autocomplete="{{ $field->getAttribute('autocomplete') }}" @required($field->required) @disabled($field->disabled) @readonly($field->readonly)>
    @endif
    @if ($helper = $field->getAttribute('helper_text'))<div id="{{ $fieldId }}-help" class="form-text">{!! $helper !!}</div>@endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
