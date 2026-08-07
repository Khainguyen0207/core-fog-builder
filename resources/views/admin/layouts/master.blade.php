<!DOCTYPE html>

<html class="light-style layout-menu-fixed overflow-x-hidden" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | FogDeveloper - Admin Template </title>

    <meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.png') }}" />

    @include('admin.layouts.sections.styles')

    @include('admin.layouts.sections.scriptsIncludes')
</head>
<body>
    @include('admin.layouts.toasts')

    @yield('layoutContent')

    @stack('modals')
</body>
@include('admin.layouts.sections.scripts')
</html>
