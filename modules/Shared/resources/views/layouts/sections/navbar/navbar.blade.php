@php
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
    $menuItems = ($sharedMenuRegistry ?? app(\Modules\Shared\Menu\MenuRegistry::class))->items();
    $resolveSearchItems = function (array $items) use (&$resolveSearchItems): array {
        return array_map(function (array $item) use (&$resolveSearchItems): array {
            if (!empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])) {
                $item['url'] = ltrim((string) parse_url(route($item['route']), PHP_URL_PATH), '/');
            } elseif (!empty($item['url'])) {
                $item['url'] = ltrim($item['url'], '/');
            }
            $item['slug'] = (array) ($item['active'] ?? ($item['route'] ?? []));
            if (!empty($item['children'])) $item['children'] = $resolveSearchItems($item['children']);
            return $item;
        }, $items);
    };
    $searchMenuItems = $resolveSearchItems($menuItems);
    $presentedUser = app(\Modules\Shared\View\NavbarUserPresenter::class)->present(auth()->user());
    $avatar = asset(trim(config('figure-admin-shared.user.avatar_path', 'assets/img/avatars'), '/').'/'.$presentedUser['avatar']);
    $logoutRoute = config('figure-admin-shared.logout.route');
    $logoutUrl = $logoutRoute && \Illuminate\Support\Facades\Route::has($logoutRoute)
        ? route($logoutRoute)
        : url(config('figure-admin-shared.logout.url', '/admin/logout'));
@endphp

<div data-shared-menu-search>
<nav class="layout-navbar {{ $navbarDetached ? $containerNav.' '.$navbarDetached : '' }} navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    @if (!$navbarDetached)<div class="{{ $containerNav }}">@endif
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)" data-shared-menu-toggle><i class="bx bx-menu bx-md"></i></a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center navbar-search-wrapper">
                <i class="bx bx-search bx-md"></i>
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search [CTRL + K]" aria-label="Search" autocomplete="off" data-shared-menu-search-trigger>
            </div>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0)" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online"><img src="{{ $avatar }}" alt="Avatar" class="w-px-40 h-auto rounded-circle"></div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-online me-3"><img src="{{ $avatar }}" alt="Avatar" class="w-px-40 h-auto rounded-circle"></div>
                            <div><h6 class="mb-0">{{ $presentedUser['email'] }}</h6><small class="text-muted">{{ $presentedUser['role'] }}</small></div>
                        </div>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li><a class="dropdown-item" href="{{ $logoutUrl }}"><i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span></a></li>
                </ul>
            </li>
        </ul>
    </div>
    @if (!$navbarDetached)</div>@endif
</nav>
    <div class="modal fade menu-search-modal" tabindex="-1" aria-hidden="true" data-shared-menu-search-modal>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 700px; width: calc(100% - 2rem);">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="h3 pb-5 position-absolute">Search</p>
                    <input type="text" class="form-control mt-10" placeholder="Search menu" aria-label="Search menu" autocomplete="off" data-shared-menu-search-input>
                    <button type="button" class="border btn-close rounded-5 shadow border-gray border-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"><div class="row g-3" data-shared-menu-search-results></div><div class="text-muted mt-3 d-none" data-shared-menu-search-empty>No results</div></div>
            </div>
        </div>
    </div>
    <script type="application/json" data-shared-menu-registry>{!! json_encode($searchMenuItems, JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
</div>
