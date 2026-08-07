@php
    $currentRoute = $form->getRoute();
    $route = \Illuminate\Support\Str::beforeLast($currentRoute->getName(), '.');

    if ($form->getModel()->getKey()) {
        $action = route($route . '.update', $form->getModel()->getKey());
    } else {
        $action = route($route . '.store');
    }
@endphp

<form class="form-sample col-12" method="POST" action="{{ $action }}" enctype="multipart/form-data"
    data-bs-target="form-{{ $id }}" id="{{ $id }}-generate-form" novalidate>
    @method($form->getMethod())
    @csrf
    <div class="row flex-row-reverse">
        <div class="col-md-3 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-4">Action</h3>
                    <button type="submit" class="btn btn-primary btn-icon-text mb-3 w-100">
                        <i class="bx bx-save btn-icon-prepend me-2"></i> Submit
                    </button>
                    <a href="{{ session('previous_table_url', route($route . '.index')) }}"
                        class="btn btn-danger btn-icon-text mb-3 w-100">
                        <i class="bx bx-exit btn-icon-prepend me-2"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-section mb-4">
                        <div class="row" data-bs-target="src-form">
                            @foreach ($form->getFields() as $field)
                                @include($field->getViewPath(), [
                                    'data' => data_get($form->getModel(), $field->getName()),
                                    'route' => Str::afterLast($currentRoute->getName(), '.'),
                                ])
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
