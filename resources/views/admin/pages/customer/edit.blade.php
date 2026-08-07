@extends('admin.layouts.contentLayout')

@section('content')
    <div class="content-wrapper">
        <div class="row g-6">
            <div class="form-has-data" id="customer-generate"
                 data-url="{{ route('admin.customers.update', $customer)}}">

                @include('admin.pages.customer.base-form')
            </div>
        </div>
    </div>
@endsection
