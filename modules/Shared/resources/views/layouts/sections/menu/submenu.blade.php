<ul class="menu-sub">
    @foreach ($items as $item)
        @php
            $patterns = (array) ($item['active'] ?? ($item['route'] ?? []));
            $active = collect($patterns)->contains(fn ($pattern) => \Illuminate\Support\Str::is($pattern, $currentRoute));
        @endphp
        <li class="menu-item {{ $active ? 'active' : '' }}">
            <a href="{{ $itemUrl($item) }}" class="menu-link {{ !empty($item['children']) ? 'menu-toggle' : '' }}">
                @if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                <div>{{ __($item['name'] ?? '') }}</div>
            </a>
            @if(!empty($item['children']))
                @include('shared::layouts.sections.menu.submenu', compact('itemUrl', 'currentRoute') + ['items' => $item['children']])
            @endif
        </li>
    @endforeach
</ul>
