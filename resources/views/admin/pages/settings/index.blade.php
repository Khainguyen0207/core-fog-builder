@extends('admin.components.panels.base')

@section('content-panel')
    <h3>{{ $title ?? 'Default' }}</h3>
    <div class="row row-cols-md-3 row-cols-lg-4 row-cols-sm-2 row-cols-1 g-3">
        @foreach ($panelSection->getPanels() as $panel)
            @include($panel->getTemplate(), [$panel])
        @endforeach
    </div>
@endsection
