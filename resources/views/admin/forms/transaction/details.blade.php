@php
    use App\Enums\BaseEnum;
    use Illuminate\Support\Str;

    $currentRoute = $form->getRoute();
    $route = Str::beforeLast($currentRoute->getName(), '.');

    if ($form->getModel()->getKey()) {
        $action = route($route . '.update', $form->getModel()->getKey());
    } else {
        $action = route($route . '.store');
    }
@endphp

<form class="form-sample col-12 needs-validation" method="POST" action="{{ $action }}" enctype="multipart/form-data"
    data-bs-target="form-{{ $id }}" id="{{ $id }}-generate-form" novalidate>
    @method($form->getMethod())
    @csrf
    <div class="row flex-row-reverse">
        <div class="col-md-3 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-4">Action</h4>
                    <button type="submit" class="btn btn-primary btn-icon-text mb-3 ">
                        <span class="icon-base bx bx-save me-2"> </span> Submit
                    </button>

                    <a href="{{ route($route . '.index') }}" class="btn btn-danger btn-icon-text mb-3">
                        <span class="icon-base bx bx-exit me-2"> </span> Cancel
                    </a>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-4">Status</h4>
                    @php
                        $field = array_filter($form->getFields(), fn($form) => $form->getName() === 'status');
                        $field = array_values($field)[0];
                        $value = data_get($form->getModel(), $field->getName());
                    @endphp

                    @include($field->getViewPath(), [
                        'data' => $value,
                        'route' => Str::afterLast($currentRoute->getName(), '.'),
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-section mb-4">
                        <h4 class="text-primary mb-3">Transaction Information</h4>
                        <div class="row" data-bs-target="src-form">
                            @foreach ($form->getFields() as $field)
                                <div class="col-md-4 col-sm-6 col-12">
                                    @php
                                        $value = data_get($form->getModel(), $field->getName());

                                        if ($field->getName() === 'response' && is_array($value)) {
                                            $value = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                        }

                                        if ($field->getName() === 'provider_id') {
                                            $value = $form->getModel()->paymentSetting?->provider_name ?? '-';
                                        }
                                    @endphp

                                    <h5 class="mb-1">{{ $field->getLabel() }}</h5>

                                    @if ($value instanceof BaseEnum)
                                        {!! $value->toHtml() !!}
                                    @elseif($field->getName() === 'response')
                                        <pre class="bg-light p-2 rounded small" style="max-height: 200px; overflow: auto;">{{ $value ?? '_' }}</pre>
                                    @else
                                        <p class="text-black">{!! $value ?? '_' !!}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })
</script>
