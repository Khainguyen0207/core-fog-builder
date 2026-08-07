@php use \Illuminate\Support\Facades\Storage; @endphp
@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer</h4>
                        <div class="action float-right">
                            <button type="reset" class="btn btn-inverse-primary btn-icon-text me-3"
                                    data-bb-toggle="btn-with-href"
                                    data-url="{{ route('admin.customers.create') }}">
                                <i class="mdi mdi-plus"></i> Thêm
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="customer-table"  data-toggle="table">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="form-check form-check-muted m-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input">
                                            </label>
                                        </div>
                                    </th>
                                    <th> ID</th>
                                    <th> Name</th>
                                    <th> Email</th>
                                    <th> Cash ( VNĐ )</th>
                                    <th> Status</th>
                                    <th> Create Date</th>
                                    <th> Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-muted m-0">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input">
                                                </label>
                                            </div>
                                        </td>
                                        <td> {{ $customer->id }}</td>
                                        <td>
                                            @if($customer->avatar && Storage::exists($customer->avatar))
                                                <img src="{{ $customer->avatar }}" alt="image"/>
                                            @endif

                                            <span class="pl-2">{{ $customer->name }}</span>
                                        </td>
                                        <td> {{$customer->email}}</td>

                                        <td> {{ number_format($customer->cash, 0) }} </td>
                                        <td>
                                            @php
                                                if ($customer->status === 'active') {
                                                    $config['status'] = 'badge-success';
                                                }
                                            @endphp

                                            {!! $customer->status->toHtml() !!}
                                        </td>
                                        <td> 04 Dec 2019</td>
                                        <td>
                                            <a href="{{route('admin.customers.show', $customer)}}" type="button"
                                               class="btn-sm btn-inverse-primary btn-icon">
                                                <i class="mdi mdi-pencil-box-outline"></i>
                                            </a>
                                            <a href="{{ route('admin.customers.destroy', $customer) }}" type="button"
                                               class="btn-sm btn-inverse-danger btn-icon"
                                               data-bs-action="modal-confirm-action" data-bs-toggle="modal"
                                               data-bs-target="#modal-confirm-delete">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
