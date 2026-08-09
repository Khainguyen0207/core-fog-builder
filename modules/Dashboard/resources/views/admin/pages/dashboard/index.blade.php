@extends('shared::layouts.content')

@section('title', 'Analytics')

@section('content')
    @include('dashboard::admin.pages.dashboard.components.kpi-row')

    @include('dashboard::admin.pages.dashboard.components.revenue-services-row')

    @include('dashboard::admin.pages.dashboard.components.activities-payments-row')
@endsection

@push('scripts')
    <script type="application/json" id="dashboard-data">{!! json_encode([
        'charts' => $charts,
        'paymentStats' => $paymentStats,
    ], JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script>const dashboardData = JSON.parse(document.getElementById('dashboard-data').textContent); window.dashboardCharts = dashboardData.charts; window.paymentStats = dashboardData.paymentStats;</script>
    @vite('modules/Dashboard/resources/js/dashboard.js')
@endpush

@push('styles')
    @vite('modules/Dashboard/resources/scss/dashboard.scss')
@endpush
