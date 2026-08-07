@php
    use Illuminate\Support\Facades\Storage;
    $name = $field->getName();
    $label = $field->getLabel();
    $type = $field->getType();
    $class = $field->getAttribute('class');
    $span = $field->getSpan();
    $placeholder = $field->getAttribute('placeholder');

    $col = match ($span) {
        1 => 'col-6',
        2 => 'col-12',
        default => 'col',
    };

    $data = $data ?? null;
    if ($data === '' || $data === null) {
        $data = $field->getDefaultValue();
    }
@endphp

<div @class([
    'col',
    'form-password-toggle' => $type === 'password',
    $col,
    'mb-3',
])>
    <label for="{{ $name }}" class="form-label">{{ $field->getLabel() }}</label>

    @switch($type)
        @case('password')
            <div class="input-group input-group-merge">
                <input type="{{ $type }}"
                    class="form-control @if (old($name) !== null && !$errors->has($name)) is-valid @endif
                    @error($name) is-invalid @enderror"
                    id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}"
                    aria-describedby="basic-default-password" {{ $route === 'create' ? 'required' : '' }} autocomplete="off">
                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
            </div>
        @break

        @case('file')
            <div class="file-upload-wrapper">
                <div class="file-upload-input">
                    @if ($field->preview)
                        <div class="file-upload-preview mb-3 text-center">
                            <img src="{{ $data ? Storage::url($data) : 'https://placehold.co/150x150' }}" alt="Preview"
                                class="img-fluid rounded-circle" id="preview"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="d-flex flex-wrap">
                        <input type="file"
                            class="form-control  @if (old($name) !== null && !$errors->has($name)) is-valid @endif
                        @error($name) is-invalid @enderror"
                            id="{{ $name }}" name="{{ $name }}" accept="{{ $field->getAccept() }}"
                            value="{{ $data ?? '' }}" {{ $field->required && $data === '' ? 'required' : '' }}
                            @if ($field->multiple) multiple @endif>

                        <a class="btn btn-primary my-2 text-white" data-bs-toggle="remove">Xóa File</a>
                    </div>
                </div>
            </div>
        @break

        @default
            <input name="{{ $name }}" id="{{ $name }}" type="{{ $type }}"
                placeholder="{{ $placeholder }}" {{ $field->required && $data === '' ? 'required' : '' }}
                class="{{ $class }} @if (old($name) !== null && !$errors->has($name)) {{ 'is-valid' }} @endif @error($name) {{ 'is-invalid' }} @enderror"
                autocomplete={{ $field->getAttribute('autocomplete') }} value="{{ old($name) ?? ($data ?? '') }}">
    @endswitch

    @if ($helper = $field->getAttribute('helper_text'))
        <div id="{{ $field->getName() . '_helper_text' }}" class="form-text">
            {!! $helper !!}
        </div>
    @endif

    @if (count($errors) === 0 || !$errors->get($name))
        <div class="invalid-feedback">{{ $field->getLabel() }} is required.</div>
    @else
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }} </div>
        @enderror
    @endif
</div>

@push('pricing-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($type == 'file')
                $('#{{ $name }}').on('change', function(e) {
                    const file = this.files[0];
                    const $preview = $('#preview');

                    if (!file || !file.type.startsWith('image/')) {
                        $preview.attr('src', 'https://placehold.co/150x150');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = e => $preview.attr('src', e.target.result);
                    reader.readAsDataURL(file);
                });
            @endif
            $('[data-bs-toggle="remove"]').on('click', function(e) {
                $('#preview').attr('src', 'https://placehold.co/150x150');
                $('#{{ $name }}[type="file"]').val(null)
            })
        })
    </script>
@endpush
