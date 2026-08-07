@extends('admin.layouts.contentLayout')

@section('title', 'Analytics')

@section('content')
    @include('admin.pages.dashboard.components.kpi-row')

    @include('admin.pages.dashboard.components.revenue-services-row')

    @include('admin.pages.dashboard.components.activities-payments-row')
@endsection

@push('scripts')
    <script>
        window.dashboardCharts = @json($charts);
        window.paymentStats = @json($paymentStats);
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush
