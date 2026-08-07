@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;

    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';

    $user = \auth()->user();

    $verticalMenuPath = resource_path('views/admin/assets/menu/verticalMenu.json');
    $verticalMenuData = [];
    if (file_exists($verticalMenuPath)) {
        $verticalMenuData = json_decode(file_get_contents($verticalMenuPath), true) ?: [];
    }
    $verticalMenuItems = $verticalMenuData['menu'] ?? [];
@endphp


@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros', ['width' => 25, 'withbg' => 'var(--bs-primary)'])</span>
            <span
                class="app-brand-text demo menu-text fw-bold text-heading">{{ config('variables.templateName') }}</span>
        </a>
    </div>
@endif


@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center navbar-search-wrapper">
            <i class="bx bx-search bx-md"></i>
            <input type="text" id="nav-search-trigger" class="form-control border-0 shadow-none ps-1 ps-sm-2"
                placeholder="Search [CTRL + K]" aria-label="Search..." autocomplete="off">
        </div>
    </div>

    <ul class="navbar-nav flex-row align-items-center ms-auto">
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/' . ($user->avatar ?? 'default-avatar.png')) }}" alt
                        class="w-px-40 h-auto rounded-circle">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">

                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="{{ asset('assets/img/avatars/' . ($user->avatar ?? 'default-avatar.png')) }}"
                                        alt class="w-px-40 h-auto rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ auth()->guard()->user()?->email }}</h6>
                                <small
                                    class="text-muted">{{ auth()->guard()->user()?->group_role->getLabel() }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                {{-- <li>
                    <a class="dropdown-item" href="{{ route('admin.users.show', auth()->guard()->user()) }}">
                        <i class="bx bx-user bx-md me-3"></i><span>Profile</span>
                    </a>
                </li> --}}
                <li>
                    <div class="dropdown-divider my-1"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}">
                        <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>

</nav>


@push('modals')
    <div class="modal fade menu-search-modal" id="menuSearchModal" tabindex="-1" aria-hidden="true"
        data-menu='@json($verticalMenuItems)'>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
            style="max-width: 700px; width: calc(100% - 2rem);">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="h3 pb-5 position-absolute">Search</p>
                    <input type="text" id="menuSearchInput" class="form-control mt-10" placeholder="Search menu..."
                        aria-label="Search menu" autocomplete="off">
                    <button type="button" class="border btn-close rounded-5 shadow border-gray border-2"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="menuSearchResults" class="row g-3"></div>
                    <div id="menuSearchEmpty" class="text-muted mt-3 d-none">No results</div>
                </div>
            </div>
        </div>
    </div>
@endpush
