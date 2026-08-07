@php
    use App\Enums\BaseEnum;
    use Illuminate\Support\Str;
    $name = $field->getName();
    $label = $field->getLabel();
    $type = $field->getType();
    $span = $field->getSpan();
    $class = $field->getAttribute('class');
    $placeholder = $field->getAttribute('placeholder');
    $multiple = $field->isMultiple() ? 'multiple' : '';
    $arrayValues = $multiple && $field->getValue() ? $field->getValue() : [];

    if (isset($data) && $data instanceof BaseEnum) {
        $data = $data->getValue();
    }

    if ($data === '' || $data === null) {
        if (old($name) != null) {
            $data = old($name);
        } else {
            $data = $field->getDefaultValue();
        }
    }

    $col = match ($span) {
        1 => 'col-6',
        2 => 'col-12',
        default => 'col',
    };
@endphp

<div class="col {{ $col }} mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>

    <div class="dropdown bootstrap-select w-100">

        <select class="selectpicker w-100" data-style="btn-default" tabindex="null"
            name="{{ $multiple ? $name . '[]' : $name }}" id="{{ $name }}" {{ $multiple }}>
            @if ($field->isFilter())
                <option value="" {{ $data && $data == $option ? 'selected' : '' }}>
                    Select {{ $label }}
                </option>
            @endif
            @foreach ($field->getOptions() as $option => $value)
                @php
                    $selected = '';
                    $optionStr = (string) $option;

                    if ($multiple) {
                        $checkArray = is_array($data) ? $data : $arrayValues;
                        if (in_array($optionStr, array_map('strval', $checkArray))) {
                            $selected = 'selected';
                        }
                    } else {
                        if (!empty($data) && !is_array($data) && (string) $data === $optionStr) {
                            $selected = 'selected';
                        }
                    }
                @endphp

                <option value="{{ $option }}" {{ $selected }}> {{ $value }}
                </option>
            @endforeach
        </select>

        @if ($helper = $field->getAttribute('helper_text'))
            <div id="{{ $field->getName() . '_helper_text' }}" class="form-text">
                {!! $helper !!}
            </div>
        @endif
    </div>

    @if (count($errors) === 0 || !$errors->get($name))
        <div class="invalid-feedback">{{ $field->getLabel() }} is required</div>
    @else
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }} </div>
        @enderror
    @endif
</div>
