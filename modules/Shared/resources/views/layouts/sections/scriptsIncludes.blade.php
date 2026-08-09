@foreach (config('figure-admin-shared.assets.scripts', []) as $script)
    <script async defer src="{{ $script }}"></script>
@endforeach
