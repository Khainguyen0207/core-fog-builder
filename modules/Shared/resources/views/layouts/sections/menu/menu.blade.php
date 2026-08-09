@php
    $menuItems = ($sharedMenuRegistry ?? app(\Modules\Shared\Menu\MenuRegistry::class))->items();
    $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
    $isActive = function (array $item) use ($currentRoute): bool {
        $patterns = (array) ($item['active'] ?? ($item['route'] ?? []));
        if (collect($patterns)->contains(fn ($pattern) => \Illuminate\Support\Str::is($pattern, $currentRoute))) return true;
        return collect($item['children'] ?? [])->contains(function (array $child) use ($currentRoute): bool {
            $patterns = (array) ($child['active'] ?? ($child['route'] ?? []));
            return collect($patterns)->contains(fn ($pattern) => \Illuminate\Support\Str::is($pattern, $currentRoute));
        });
    };
    $itemUrl = function (array $item): string {
        if (!empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])) return route($item['route']);
        return !empty($item['url']) ? url($item['url']) : 'javascript:void(0)';
    };
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-shared-menu>
    <div class="app-brand demo">
        <a href="{{ url(config('figure-admin-shared.branding.home_url', '/')) }}" class="app-brand-link" aria-label="Dashboard">
            <span class="app-brand-logo demo flex-shrink-0"><img src="{{ asset(config('figure-admin-shared.branding.logo')) }}" alt="Logo"></span>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner pt-1" style="padding-bottom: 50px">
        @foreach ($menuItems as $item)
            @php($active = $isActive($item))
            <li class="menu-item {{ $active ? (!empty($item['children']) ? 'active open' : 'active') : '' }}">
                <a href="{{ $itemUrl($item) }}" class="menu-link {{ !empty($item['children']) ? 'menu-toggle' : '' }}" @if(!empty($item['target'])) target="{{ $item['target'] }}" @endif>
                    @if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                    <div>{{ __($item['name'] ?? '') }}</div>
                </a>
                @if(!empty($item['children']))
                    @include('shared::layouts.sections.menu.submenu', compact('itemUrl', 'currentRoute') + ['items' => $item['children']])
                @endif
            </li>
        @endforeach
    </ul>
</aside>
