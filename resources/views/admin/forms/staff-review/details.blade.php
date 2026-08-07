@php
use App\Enums\BaseEnum;
use Illuminate\Support\Str;

$currentRoute = $form->getRoute();
$route = Str::beforeLast($currentRoute->getName(), '.');
@endphp

<div class="row flex-row-reverse">
    <div class="col-md-3 col-12">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-4">Action</h4>
                <a href="{{ route($route.'.index') }}" class="btn btn-secondary btn-icon-text mb-3">
                    <span class="icon-base bx bx-arrow-back me-2"></span> Back to List
                </a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-4">Rating</h4>
                <div class="d-flex align-items-center mb-2">
                    @for($i = 1; $i <= 5; $i++) @if($i <=$form->getModel()->rating)
                        <span class="icon-base bx bxs-star text-warning fs-4"></span>
                        @else
                        <span class="icon-base bx bx-star text-muted fs-4"></span>
                        @endif
                        @endfor
                </div>
                <p class="mb-0 text-muted">{{ $form->getModel()->rating }}/5 Stars</p>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-section mb-4">
                    <h4 class="text-primary mb-3">Review Information</h4>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <h5 class="mb-1">Customer</h5>
                            <p class="text-black">{{ $form->getModel()->customer?->name ?? '-' }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <h5 class="mb-1">Staff</h5>
                            <p class="text-black">{{ $form->getModel()->staff?->name ?? '-' }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <h5 class="mb-1">Service</h5>
                            <p class="text-black">{{ $form->getModel()->bookingService?->service_name ?? '-' }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <h5 class="mb-1">Booking</h5>
                            <p class="text-black">
                                <a href="{{ route('admin.bookings.show', $form->getModel()->bookingService?->booking_id) }}">{{ $form->getModel()->bookingService?->booking->booking_code ?? '-' }}</a>
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <h5 class="mb-1">Created At</h5>
                            <p class="text-black">{{ $form->getModel()->created_at?->format('d/m/Y H:i:s') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if($form->getModel()->note)
                <div class="form-section">
                    <h4 class="text-primary mb-3">Note</h4>
                    {!! $form->getModel()->note !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
