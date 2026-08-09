@extends('shared::layouts.content')

@section('title', $title)
@section('content')
    <h3>{{ $title ?? 'Default' }}</h3>
    <div class="row row-cols-md-3 row-cols-lg-4 row-cols-sm-2 row-cols-1 g-3">
        @foreach ($panelSection->getPanels() as $panel)
            @include($panel->getTemplate(), compact('panel'))
        @endforeach
    </div>
@endsection
