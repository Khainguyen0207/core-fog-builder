@php
    use App\Enums\BaseEnum;
    use Illuminate\Support\Arr;
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

                    <a href="#" data-url="{{ route($route . '.export') }}" data-fd-toggle="btn-export-invoice"
                        data-id="{{ $form->getModel()->getKey() }}" class="btn btn-secondary btn-icon-text mb-3">
                        <span class="icon-base bx bx-export me-2"></span>
                        Invoice
                    </a>

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
                        <h4 class="text-primary mb-3">Booking Information</h4>
                        <div class="row" data-bs-target="src-form">
                            @foreach ($form->getFields() as $field)
                                <div class="col-md-4 col-sm-6 col-12">
                                    @php
                                        $value = data_get($form->getModel(), $field->getName());
                                    @endphp

                                    <h5 class="mb-1">{{ $field->getLabel() }}</h5>

                                    @if ($value instanceof BaseEnum)
                                        {!! $value->toHtml() !!}
                                    @else
                                        <p class="text-black">{!! $value ?? '_' !!}</p>
                                    @endif
                                </div>
                            @endforeach

                            <div class="col-md-4 col-sm-6 col-12">
                                <h5 class="mb-1">Transaction Status</h5>
                                {!! $form->getModel()->transaction?->status->toHtml() ?? '_' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="mb-3">
                    <p class="mb-1">Note</p>
                    <div id="full-editor" class="shadow-sm border">{!! $form->getModel()->note !!}</div>
                    <input type="hidden" name="note" id="input-hidden-full-editor">
                </div>
            </div>
            <div class="row my-3">
                <h4 class="text-primary mb-3">Services Information</h4>
                @foreach ($form->getModel()->bookingServices as $bookingService)
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>{{ $bookingService->service_name }}</strong>

                                {!! $bookingService->status->toHtml() !!}
                            </div>

                            <div class="card-body">
                                <p class="mb-1">
                                    <strong>Booking Code:</strong>
                                    {{ $bookingService->booking->booking_code }}
                                </p>

                                <p class="mb-1">
                                    <strong>Booking Service ID:</strong>
                                    {{ $bookingService->getKey() }}
                                </p>

                                <p class="mb-1">
                                    <strong>Price:</strong>
                                    {{ number_format($bookingService->price, 0, ',', '.') }} VND
                                </p>

                                <p class="mb-1">
                                    <strong>Duration:</strong>
                                    {{ $bookingService->duration }} phút
                                </p>

                                <p class="mb-1">
                                    <strong>Staff:</strong>
                                    @if ($bookingService->staff)
                                        <a class="text-info"
                                            href="{{ route('admin.staffs.edit', $bookingService->staff->id) }}">{{ $bookingService->staff->name }}
                                        </a>
                                        ({{ $bookingService->staff->staff_code }})
                                    @else
                                        _
                                    @endif
                                </p>

                                @if ($bookingService->note)
                                    <p class="mb-1">
                                        <strong>Note:</strong>
                                        {{ $bookingService->note }}
                                    </p>
                                @endif

                                <hr>

                                <p class="mb-1">
                                    <strong>Start:</strong>
                                    {{ $bookingService->started_at }}
                                </p>

                                <p class="mb-0">
                                    <strong>Finish:</strong>
                                    {{ $bookingService->finished_at }}
                                </p>
                            </div>

                            @if ($bookingService->staffReview)
                                <div class="card-body border-top bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <strong class="me-2">Đánh giá:</strong>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $bookingService->staffReview->rating)
                                                <span class="icon-base bx bxs-star text-warning"></span>
                                            @else
                                                <span class="icon-base bx bx-star text-muted"></span>
                                            @endif
                                        @endfor
                                        <span
                                            class="ms-2 text-muted">({{ $bookingService->staffReview->rating }}/5)</span>
                                    </div>
                                    @if ($bookingService->staffReview->note)
                                        <p class="mb-1 small">
                                            <strong>Nhận xét:</strong>
                                            {{ $bookingService->staffReview->note }}
                                        </p>
                                    @endif
                                    <p class="mb-0 small text-muted">
                                        Đánh giá lúc:
                                        {{ $bookingService->staffReview->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            @endif

                            <div class="card-footer small">
                                Created at: {{ $bookingService->created_at }}
                            </div>
                        </div>
                    </div>
                @endforeach
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

        $(document).on('click', '[data-fd-toggle="btn-export-invoice"]', function(e) {
            e.preventDefault();

            const $btn = $(this);
            const url = $btn.data('url');
            const id = $btn.data('id');

            if (!url || !id) {
                console.error('Thiếu data-url hoặc data-id');
                return;
            }

            const csrf = $('meta[name="csrf-token"]').attr('content');

            const $form = $('<form>', {
                method: 'POST',
                action: url
            });

            $form.append(
                $('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrf
                }), $('<input>', {
                    type: 'hidden',
                    name: 'id',
                    value: id
                })
            );

            $('body').append($form);
            $form.submit();
        });
    })
</script>
