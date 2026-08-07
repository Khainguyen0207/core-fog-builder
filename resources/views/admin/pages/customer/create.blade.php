@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <h3 class="card-title">Customer Information</h3>
            <div class="form-has-data" id="customer-generate" data-url="{{ route('admin.customers.store') }}">
                @include('admin.pages.customer.base-form')
            </div>
        </div>
    </div>
@endsection
