@extends('shared::layouts.content')

@section('title', 'Plugins')

@section('content')
    <div class="w-100">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h3 class="mb-1">Plugins</h3>
                <p class="text-secondary mb-0">Optional features are disabled by default. Required dependencies must be enabled together.</p>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-label-secondary">Back to Settings</a>
        </div>

        <form method="POST" action="{{ route('admin.settings.plugins.update') }}">
            @csrf

            <div class="row g-3">
                @foreach ($statuses as $status)
                    <div class="col-12 col-lg-6">
                        <div class="card h-100">
                            <div class="card-body d-flex gap-3">
                                <div class="form-check form-switch mt-1">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        name="enabled_plugins[]"
                                        value="{{ $status->definition->packageName }}"
                                        id="plugin-{{ Str::slug($status->definition->packageName) }}"
                                        @checked($status->requested)
                                        @disabled(! $status->installed)
                                    >
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <label class="h5 mb-0" for="plugin-{{ Str::slug($status->definition->packageName) }}">
                                            {{ $status->definition->displayName }}
                                        </label>
                                        <span class="badge bg-label-{{ $status->enabled ? 'success' : ($status->installed ? 'secondary' : 'danger') }}">
                                            {{ str($status->reason)->replace('_', ' ')->title() }}
                                        </span>
                                    </div>
                                    <code class="d-block mt-2">{{ $status->definition->packageName }}</code>

                                    <div class="mt-3">
                                        <span class="text-secondary">Dependencies:</span>
                                        @forelse ($status->definition->dependencies as $dependency)
                                            <span class="badge bg-label-info ms-1">{{ $dependency }}</span>
                                        @empty
                                            <span class="text-secondary ms-1">None</span>
                                        @endforelse
                                    </div>

                                    @if ($status->blockers !== [])
                                        <p class="text-danger small mt-3 mb-0">Unavailable: {{ implode(', ', $status->blockers) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('enabled_plugins')
                <div class="alert alert-danger mt-4 mb-0">{{ $message }}</div>
            @enderror

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">Save Plugins</button>
            </div>
        </form>
    </div>
@endsection
