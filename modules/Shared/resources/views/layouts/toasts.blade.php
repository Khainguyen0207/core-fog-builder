@php
    $toastMessages = collect(['success', 'error', 'warning', 'info'])
        ->filter(fn (string $type) => session()->has($type))
        ->map(fn (string $type) => [
            'type' => $type,
            'title' => ucfirst($type),
            'message' => session($type),
        ])->values();

    if ($errors->any()) {
        $toastMessages->push([
            'type' => 'error',
            'title' => $errors->count() === 1 ? 'Validation Error' : 'Validation Errors',
            'message' => $errors->count() === 1 ? $errors->first() : implode('\n', $errors->all()),
        ]);
    }
@endphp
<section id="toast-section" data-shared-toasts>
    <div class="align-items-end toast-container top-right" data-shared-toast-container></div>
    <script type="application/json" data-shared-toast-config>{!! json_encode($toastMessages, JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
</section>
