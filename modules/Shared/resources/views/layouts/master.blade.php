<!DOCTYPE html>
<html class="light-style layout-menu-fixed overflow-x-hidden" data-theme="theme-default"
    data-assets-path="{{ rtrim(asset(config('figure-admin-shared.layout.assets_path', '/assets')), '/') }}/"
    data-base-url="{{ url('/') }}" data-framework="laravel">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>@yield('title') | {{ config('figure-admin-shared.branding.name') }} - {{ config('figure-admin-shared.branding.title_suffix') }}</title>
    <meta name="description" content="{{ config('figure-admin-shared.branding.description', '') }}">
    <meta name="keywords" content="{{ config('figure-admin-shared.branding.keywords', '') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (config('figure-admin-shared.branding.canonical_url'))
        <link rel="canonical" href="{{ config('figure-admin-shared.branding.canonical_url') }}">
    @endif
    <link rel="icon" type="image/x-icon" href="{{ asset(config('figure-admin-shared.branding.favicon')) }}">
    @include('shared::layouts.sections.styles')
    @include('shared::layouts.sections.scriptsIncludes')
</head>
<body>
    @include('shared::layouts.toasts')
    @yield('layoutContent')
    @stack('modals')
    @include('shared::layouts.sections.scripts')
</body>
</html>
