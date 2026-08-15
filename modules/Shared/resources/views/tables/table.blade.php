@php
    $tableId = 'data-table-'.\Illuminate\Support\Str::slug($name);
    $filterId = 'filter-'.\Illuminate\Support\Str::slug($name);
    $filterCollapseId = 'filter-collapse-'.\Illuminate\Support\Str::slug($name);
    $routeNamePrefix = config('figure-admin-shared.routes.name_prefix', 'admin.');
    $dataRoute = $routeNamePrefix.'get-data';
    $bulkDeleteRoute = $routeNamePrefix.'bulk-delete';
@endphp
<div class="card card-datatable px-4 pb-4 w-100" data-shared-table data-table-resource="{{ $name }}">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4 mb-2">
        <h4 class="col-md-auto m-0">{{ $table->getNameTable() }}</h4>
        <div class="header-action d-flex flex-wrap align-items-center gap-2">
            @if ($table->isHasBulkDelete() && \Illuminate\Support\Facades\Route::has($bulkDeleteRoute))
                <button type="button" class="btn btn-outline-danger me-3 mb-3 d-none" data-shared-bulk-trigger data-bs-toggle="modal" data-bs-target="#bulk-confirm-modal-{{ \Illuminate\Support\Str::slug($name) }}"><span class="bx bx-trash me-2"></span>Delete (<span data-shared-bulk-count>0</span>)</button>
            @endif
            @foreach ($table->getHeaderActions() as $action) @include($action->getTemplate(), compact('action')) @endforeach
            @if ($table->hasHeaderAction() && empty($table->getHeaderActions()) && \Illuminate\Support\Facades\Route::has($table->route.'create'))
                <a href="{{ route($table->route.'create') }}" class="btn btn-outline-info me-3 mb-3"><span class="bx bx-plus me-2"></span>Create</a>
            @endif
            @if ($table->isHasFilter())
                <button type="button" class="btn btn-outline-primary me-3 mb-3" data-bs-toggle="collapse" data-bs-target="#{{ $filterCollapseId }}" aria-expanded="false" aria-controls="{{ $filterCollapseId }}"><span class="bx bx-filter-alt me-2"></span>Filter</button>
            @endif
            @if (\Illuminate\Support\Facades\Route::has($table->route.'index'))<a href="{{ route($table->route.'index') }}" class="btn btn-outline-secondary me-3 mb-3"><span class="bx bx-refresh me-2"></span>Reload</a>@endif
        </div>
    </div>
    @if ($table->isHasFilter())
        <div id="{{ $filterCollapseId }}" class="collapse">
            <form id="{{ $filterId }}" method="GET" data-shared-table-filter>
                <div class="row row-cols-md-4 row-cols-1 row-cols-sm-2 px-2">
                    @foreach ($table->getFilters() as $field) @include($field->getViewPath(), ['fieldIdPrefix' => $filterId, 'data' => request($field->getName())]) @endforeach
                </div>
                <button type="submit" class="btn btn-outline-primary m-2"><span class="bx bx-filter-alt me-2"></span>Filter</button><button type="reset" class="btn btn-outline-info m-2"><span class="bx bx-reset me-2"></span>Clear</button>
            </form>
        </div>
    @endif
    <table class="table" id="{{ $tableId }}" data-shared-table-element>
        <thead><tr>
            @if ($table->isHasCheckBox())<th data-dt="checkbox"><input class="form-check-input" type="checkbox" data-shared-select-all aria-label="Select all rows"></th>@endif
            @foreach ($table->getColumns() as $column)<th data-field="{{ $column->getName() }}">{!! $column->getLabel() !!}</th>@endforeach
            @if ($table->hasOperationsColumn())<th data-dt="operation">Operations</th>@endif
        </tr></thead>
    </table>
    <script type="application/json" data-shared-table-config>{!! json_encode([
        'url' => route($dataRoute, ['table' => $name]),
        'columns' => json_decode($table->getColumnsToJson(), true, 512, JSON_THROW_ON_ERROR),
        'orderColumn' => $table->isHasCheckBox() ? 1 : 0,
        'bulkModalId' => $table->isHasBulkDelete() ? 'bulk-confirm-modal-'.\Illuminate\Support\Str::slug($name) : null,
    ], JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
</div>
