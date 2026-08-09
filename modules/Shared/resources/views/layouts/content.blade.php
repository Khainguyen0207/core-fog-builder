@extends('shared::layouts.master')

@php
    $contentNavbar = true;
    $containerNav = $containerNav ?? 'container-xxl';
    $isNavbar = $isNavbar ?? true;
    $isMenu = $isMenu ?? true;
    $isFlex = $isFlex ?? false;
    $isFooter = $isFooter ?? true;
    $navbarDetached = 'navbar-detached';
    $container = $container ?? 'container-xxl';
@endphp

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container">
            @if ($isMenu)
                @include('shared::layouts.sections.menu.menu')
            @endif
            <div class="layout-page">
                @if ($isNavbar)
                    @include('shared::layouts.sections.navbar.navbar')
                @endif
                <div class="content-wrapper">
                    <div class="{{ $container }} {{ $isFlex ? 'd-flex align-items-stretch flex-grow-1 p-0' : 'flex-grow-1 container-p-y' }}">
                        @yield('content')
                    </div>
                    @if ($isFooter)
                        @include('shared::layouts.sections.footer.footer')
                    @endif
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
            @if ($isMenu)
                <div class="layout-overlay layout-menu-toggle" data-shared-menu-toggle></div>
            @endif
            <div class="drag-target"></div>
        </div>
    </div>
@endsection
