@php
    $action = $form->getAction();
    $method = $form->getMethod();
    $cancelUrl = $form->getCancelUrl();
@endphp

<form class="form-sample col-12 needs-validation {{ $class }}" method="POST" action="{{ $action ?? '' }}"
    @if($form->hasFile()) enctype="multipart/form-data" @endif id="{{ $id }}" data-shared-form novalidate>
    @csrf
    @if (!in_array($method, ['GET', 'POST'], true)) @method($method) @endif
    <div class="row flex-row-reverse">
        <div class="col-md-3 col-12">
            <div class="card mb-3"><div class="card-body">
                <h3 class="mb-4">Action</h3>
                <button type="submit" class="btn btn-primary btn-icon-text mb-3 w-100"><i class="bx bx-save me-2"></i>Submit</button>
                @if ($cancelUrl)<a href="{{ $cancelUrl }}" class="btn btn-danger btn-icon-text mb-3 w-100"><i class="bx bx-exit me-2"></i>Cancel</a>@endif
            </div></div>
        </div>
        <div class="col-md-9 col-12"><div class="card"><div class="card-body"><div class="form-section mb-4">
            <div class="row" data-bs-target="src-form">
                @foreach ($form->getFields() as $field)
                    @include($field->getViewPath(), ['data' => data_get($form->getModel(), $field->getName()), 'fieldIdPrefix' => $id])
                @endforeach
            </div>
        </div></div></div></div>
    </div>
</form>
