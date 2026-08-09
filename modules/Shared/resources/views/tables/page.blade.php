@extends('shared::layouts.content')

@section('title', $table->getNameTable() ?: 'Table')
@section('content')
    @include('shared::tables.table')
@endsection

@push('modals')
    <div class="modal fade" id="confirm-modal-{{ \Illuminate\Support\Str::slug($name) }}" tabindex="-1" aria-hidden="true" data-shared-operation-modal data-shared-table-resource="{{ $name }}">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
            <div class="modal-header"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body text-center"><h3 class="text-warning">Notification</h3><p data-shared-operation-content>Are you sure?</p></div>
            <div class="modal-footer"><form action="/" method="POST" data-shared-operation-form>@csrf @method('DELETE')<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">OK</button></form></div>
        </div></div>
    </div>
    @php($bulkDeleteRoute = config('figure-admin-shared.routes.name_prefix', 'admin.').'bulk-delete')
    @if ($table->isHasBulkDelete() && \Illuminate\Support\Facades\Route::has($bulkDeleteRoute))
        <div class="modal fade" id="bulk-confirm-modal-{{ \Illuminate\Support\Str::slug($name) }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                <div class="modal-header"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body text-center"><h3 class="text-warning">Notification</h3><p>Delete <span data-shared-bulk-modal-count>0</span> selected records?</p></div>
                <div class="modal-footer"><form action="{{ route($bulkDeleteRoute) }}" method="POST">@csrf<div data-shared-bulk-ids></div><input type="hidden" name="resource" value="{{ $name }}"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Delete</button></form></div>
            </div></div>
        </div>
    @endif
@endpush
