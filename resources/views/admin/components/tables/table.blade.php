@php
    use App\Enums\BaseEnum;
    use App\Table\Columns\FormatColumn;
    use Illuminate\Support\Str;
@endphp


<div class="card card-datatable px-4" data-table-resource="{{ $name }}">
    <div class="d-flex justify-content-between ms-3 mt-5">
        <h4 class="col-md-auto m-0">{{ $table->getNameTable() }}</h4>

        <div class="header-action d-inline-block text-end d-flex align-items-center gap-2">

            @if ($table->isHasBulkDelete())
                <button type="button" class="btn btn-outline-danger col-md-auto me-3 mb-3 d-none" id="bulk-delete-btn"
                    data-bs-toggle="modal" data-bs-target="#bulk-confirm-modal">
                    <span class="icon-base bx bx-trash icon-sm me-2"></span>
                    <span>Xóa tất cả (<span class="bulk-count">0</span>)</span>
                </button>
            @endif

            @if ($table->hasHeaderAction())
                @if (!empty($table->getHeaderActions()))
                    @foreach ($table->getHeaderActions() as $action)
                        @include($action->getTemplate())
                    @endforeach
                @else
                    <a href="{{ route($table->route . 'create') }}" class="btn btn-outline-info col-md-auto me-3 mb-3">
                        <span class="icon-base bx bx-plus icon-sm me-2">
                        </span>
                        <span>Create</span>
                    </a>
                @endif
            @endif
            <a href="{{ route($table->route . 'index') }}" class="btn btn-outline-github col-md-auto me-3 mb-3">
                <span class="icon-base bx bx-refresh icon-sm me-2">
                </span>

                <span>Reload</span>
            </a>
        </div>
    </div>

    @if ($table->isHasFilter())
        <form action="{{ route('admin.get-data', [
            'table' => 'cc',
        ]) }}"
            data-bs-toggle="form-filter" id="form-filter" method="get">

            <div class="row row-cols-md-4 row-cols-1 row-cols-sm-2 px-2">
                @foreach ($table->getFilters() as $field)
                    @include($field->getViewPath())
                @endforeach
            </div>
            <button type="submit" class="btn btn-outline-primary m-2" data-ds-toggle="search-datatable">
                <span class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="m20,2H4c-.55,0-1,.45-1,1v2c0,.24.09.48.25.66l6.75,7.72v7.62c0,.4.24.77.62.92.12.05.25.08.38.08.26,0,.52-.1.71-.29l2-2c.19-.19.29-.44.29-.71v-5.62l6.75-7.72c.16-.18.25-.42.25-.66v-2c0-.55-.45-1-1-1Z">
                        </path>
                    </svg>
                </span>
                Filter
            </button>
            <button type="reset" id="btn-reset" class="btn btn-outline-info m-2" data-ds-toggle="clear-search">
                <span class="icon-base bx bx-x icon-sm "></span>

                <span>Clear</span>
            </button>
        </form>
    @endif

    <table class="table table-responsive" id="dataTable" data-url="{{ route('admin.get-data', $name) }}">
        <thead>
            <tr>
                @if ($table->isHasCheckBox())
                    <th data-dt="checkbox">
                        <input class="form-check-input" type="checkbox" id="select-all-checkbox"
                            aria-label="Select all rows">
                    </th>
                @endif

                @foreach ($table->getColumns() as $column)
                    <th data-field="{{ $column->getName() }}">{!! $column->getLabel() !!}</th>
                @endforeach

                @if ($table->hasOperationsColumn())
                    <th data-dt="operation">Operations</th>
                @endif
            </tr>
        </thead>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let dataTable = $('#dataTable');
        const dataUrl = dataTable.data('url')

        const $container = dataTable.closest('.card-datatable');
        const tableResource = $container.data('table-resource') || '';
        let cols = @json(json_decode($table->getColumnsToJson(), true));
        const isHasCheckBox = {{ $table->isHasCheckBox() ? 1 : 0 }};

        cols = cols.map(function(col) {
            if (col.render === '__ROW_CHECKBOX_RENDER__') {
                col.render = function(data, type, row) {
                    return '<input class="form-check-input row-checkbox" type="checkbox" data-row-id="' +
                        data + '" aria-label="Select row">';
                };
            }
            return col;
        });

        const colNameSet = cols.reduce(function(acc, col) {
            const name = col.name || col.data || col.mData;
            if (name) acc[name] = true;
            return acc;
        }, {});


        let selectedIds = [];

        function updateBulkUI() {
            const $bulkBtn = $container.find('#bulk-delete-btn');
            const $bulkCount = $bulkBtn.find('.bulk-count');
            const $selectAllCheckbox = $container.find('#select-all-checkbox');
            const $rowCheckboxes = $container.find('.row-checkbox');

            $bulkCount.text(selectedIds.length);

            if (selectedIds.length > 0) {
                $bulkBtn.removeClass('d-none');
            } else {
                $bulkBtn.addClass('d-none');
            }


            const totalRows = $rowCheckboxes.length;
            const checkedRows = $rowCheckboxes.filter(':checked').length;

            if (checkedRows === 0) {
                $selectAllCheckbox.prop('checked', false);
                $selectAllCheckbox.prop('indeterminate', false);
            } else if (checkedRows === totalRows && totalRows > 0) {
                $selectAllCheckbox.prop('checked', true);
                $selectAllCheckbox.prop('indeterminate', false);
            } else {
                $selectAllCheckbox.prop('checked', false);
                $selectAllCheckbox.prop('indeterminate', true);
            }
        }

        function syncSelectedIdsFromCheckboxes() {
            selectedIds = [];
            $container.find('.row-checkbox:checked').each(function() {
                const id = $(this).data('row-id');
                if (id !== undefined) {
                    selectedIds.push(id);
                }
            });
            $container.data('selected-ids', selectedIds);
            updateBulkUI();
        }


        $container.on('change', '#select-all-checkbox', function() {
            const isChecked = $(this).prop('checked');
            $container.find('.row-checkbox').prop('checked', isChecked);
            syncSelectedIdsFromCheckboxes();
        });


        $container.on('change', '.row-checkbox', function() {
            syncSelectedIdsFromCheckboxes();
        });

        loadFiltersFromUrl();

        if (dataTable.length > 0) {

            dataTable = dataTable.DataTable({
                "language": {
                    "sLengthMenu": "Show \t _MENU_entries",
                    info: `Showing _START_ to _END_ of _TOTAL_ ${$('.card-datatable h4').text().toLowerCase()}`,
                },
                "ordering": true,
                "order": [
                    [isHasCheckBox ? 1 : 0, 'desc']
                ],
                lengthMenu: [10, 20, 50],
                serverSide: true,
                processing: true,
                paging: true,
                ajax: {
                    url: dataUrl,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        const $form = $('#form-filter');
                        const filtersCols = getFiltersFromForm($form, true);
                        const filtersAll = getFiltersFromForm($form, false);

                        $.extend(d, filtersCols);

                        d.search = d.search || {};
                        d.search.value = JSON.stringify({
                            value: d.search.value || '',
                            dataSearch: filtersAll
                        });
                        d.search.regex = false;
                    },
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                drawCallback: function(settings) {
                    const api = new $.fn.dataTable.Api(settings);

                    api.rows({
                        page: 'current'
                    }).every(function() {
                        const rowData = this.data();
                        const rowNode = this.node();
                        const $cell = $(rowNode).find('a.btn[data-bs-key]');

                        Object.values($cell).forEach((r) => {
                            if ($(r).hasClass('btn')) {
                                const dataKey = $(r).attr('data-bs-dataKey')
                                    .trim() !== '' ? $(r).attr('data-bs-dataKey') :
                                    'id';

                                if ($(r).attr('href') !== '#') {
                                    let href = $(r).attr('href');
                                    href = href.replace(/\/0(\/|$)/, '/' + rowData[
                                        dataKey] + '$1');
                                    $(r).attr('href', href);
                                }

                                const value = dataKey && rowData[dataKey] !==
                                    undefined ?
                                    rowData[dataKey] :
                                    rowData.id;

                                $(r).attr('data-bs-key', value);
                            }
                        })
                    });


                    selectedIds = [];
                    $container.find('#select-all-checkbox').prop('checked', false).prop(
                        'indeterminate', false);
                    updateBulkUI();
                },
                columns: cols,
                searching: false,
                dom: '<"dt-top d-flex justify-content-start"l>rt<"dt-bottom"ip>',
            });

        }

        function getFiltersFromForm($form, onlyCols) {
            const filters = {};
            const radioProcessed = {};

            $form.find('input[name], select[name], textarea[name]').each(function() {
                if (this.disabled) return;
                const $el = $(this);
                const name = $el.attr('name');

                if (!name) return;
                if (onlyCols && !colNameSet[name]) return;

                if ($el.is(':radio')) {
                    if (radioProcessed[name]) return;
                    radioProcessed[name] = true;
                    const $checked = $form.find('input[name="' + name + '"]:checked');
                    if ($checked.length) {
                        const value = $checked.val();
                        if (value !== '' && value != null) filters[name] = value;
                    }
                    return;
                }

                if ($el.is(':checkbox')) {
                    if (!$el.prop('checked')) return;
                    const value = $el.val();
                    filters[name] = value !== '' && value != null ? value : true;
                    return;
                }

                if ($el.is('select[multiple]')) {
                    const value = $el.val();
                    if (Array.isArray(value) && value.length > 0) {
                        filters[name] = value;
                    }
                    return;
                }

                let value = $el.val();
                if (typeof value === 'string') value = value.trim();
                if (value === '' || value == null) return;
                filters[name] = value;
            });

            return filters;
        }

        function resetFilters() {
            const form = document.getElementById('form-filter');
            if (form) form.reset();
            $('#form-filter').find('select.selectpicker').selectpicker('val', "");
        }

        window.addEventListener('popstate', function() {
            resetFilters();
            loadFiltersFromUrl();
            const qs = window.location.search;
            const baseUrl = dataUrl + qs;
            if (dataTable.ajax) {
                dataTable.ajax.url(baseUrl).load();
            }
        });

        $('#form-filter').on('submit', function(e) {
            e.preventDefault();
            const qs = syncFiltersToUrl();
            const baseUrl = dataUrl + '?' + qs;
            dataTable.ajax.url(baseUrl).load();
        });

        $('#btn-reset').on('click', function(e) {
            e.preventDefault();
            resetFilters();

            dataTable.search('');
            dataTable.columns().search('');
            clearUrlParams();
            dataTable.ajax.url(dataUrl).load();
        });

        dataTable.on('click', '[data-action]', function(e) {
            e.preventDefault();
        });

        $('#create-modal').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this).find('form');

            const token = $('input[name="_token"]').val();

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: $form.serialize(),
                error: function(xhr) {
                    clearValidationErrors($form);

                    if (xhr.status === 422) {
                        const res = xhr.responseJSON || {};
                        const errors = res.errors || {};

                        renderValidationErrors($form, errors);
                        return;
                    }

                },
                success: function({
                    error,
                    data,
                    message
                }) {
                    dataTable.ajax.reload();

                    $('.btn-close').click();
                }
            })
        })

        function clearValidationErrors($form) {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').text('').addClass('d-none');
        }

        function renderValidationErrors($form, errors) {
            Object.keys(errors).forEach(function(field) {
                const messages = errors[field];
                const message = Array.isArray(messages) ? messages[0] : messages;

                const $input = $form.find(`[name="${cssEscape(field)}"]`);

                if ($input.length) {
                    $input.addClass('is-invalid');

                    let $feedback = null;

                    const $group = $input.closest('.input-group');
                    if ($group.length) {
                        $feedback = $group.find('.invalid-feedback').first();
                    }

                    if (!$feedback || !$feedback.length) {
                        const $wrapper = $input.closest('.form-group, .mb-3');
                        if ($wrapper.length) {
                            $feedback = $wrapper.find('.invalid-feedback').first();
                        }
                    }

                    if ((!$feedback || !$feedback.length)) {
                        $feedback = $input.next('.invalid-feedback');
                    }

                    if ($feedback && $feedback.length) {
                        $feedback.text(message).removeClass('d-none');
                    } else {
                        console.warn('No invalid-feedback found for field:', field);
                    }
                } else {
                    console.warn('No input found for field:', field, message);
                }
            });
        }

        function cssEscape(str) {
            if (window.CSS && CSS.escape) return CSS.escape(str);
            return str.replace(/([!"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~])/g, '\\$1');
        }

        $('#confirm-modal').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this).find('form');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function({
                    error,
                    data,
                    message
                }) {
                    if (error === true) {
                        alert(message)
                    } else {
                        dataTable.ajax.reload();
                    }

                    $('.btn-close').click();
                },
                error: function(xhr) {
                    alert("Can't do");
                },
            });
        });

        // Bulk delete modal handler
        $('#bulk-confirm-modal').on('show.bs.modal', function() {
            const $modal = $(this);
            $modal.find('.bulk-delete-count').text(selectedIds.length);
            $modal.find('#bulk-ids-input').val(JSON.stringify(selectedIds));
            $modal.find('#bulk-resource-input').val(tableResource);
        });

        $('#bulk-confirm-modal').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this).find('form');
            const idsJson = $form.find('#bulk-ids-input').val();
            const resource = $form.find('#bulk-resource-input').val();
            const ids = JSON.parse(idsJson || '[]');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    ids: ids,
                    resource: resource,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }),
                success: function(response) {
                    dataTable.ajax.reload();
                    selectedIds = [];
                    updateBulkUI();
                    $('.btn-close').click();
                },
                error: function(xhr) {
                    alert("Can't do");
                },
            });
        });

        function syncFiltersToUrl() {
            const $form = $('#form-filter');
            const urlParams = new URLSearchParams();

            $form.find('input[name], select[name], textarea[name]').each(function() {
                if (this.disabled) return;
                const $el = $(this);
                const name = $el.attr('name');
                if (!name) return;

                if ($el.is(':radio') || $el.is(':checkbox')) {
                    if ($el.prop('checked')) {
                        urlParams.append(name, $el.val());
                    }
                    return;
                }

                if ($el.is('select[multiple]')) {
                    const value = $el.val();
                    if (Array.isArray(value) && value.length > 0) {
                        value.forEach(v => urlParams.append(name + '[]', v));
                    }
                    return;
                }

                let value = $el.val();
                if (typeof value === 'string') value = value.trim();
                if (value !== '' && value != null) {
                    urlParams.set(name, value);
                }
            });

            const newUrl = window.location.pathname;
            const qs = urlParams.toString();
            const finalUrl = qs ? newUrl + '?' + qs : newUrl;
            window.history.pushState({
                path: finalUrl
            }, '', finalUrl);

            return qs ?? '';
        }

        function clearUrlParams() {
            window.history.pushState({}, '', window.location.pathname);
        }

        function loadFiltersFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            const $form = $('#form-filter');
            let hasParams = false;

            for (const [key, value] of urlParams.entries()) {
                hasParams = true;
                const baseKey = key.replace(/\[\]$/, '');
                const $el = $form.find(`[name="${baseKey}"], [name="${key}"]`);

                if ($el.length) {
                    if ($el.is(':radio') || $el.is(':checkbox')) {
                        $el.filter(`[value="${value}"]`).prop('checked', true);
                    } else if ($el.is('select[multiple]')) {
                        let currentVal = $el.val() || [];
                        if (!Array.isArray(currentVal)) currentVal = [currentVal];
                        currentVal.push(value);
                        $el.val(currentVal);
                        if ($el.hasClass('selectpicker')) {
                            $el.selectpicker('val', currentVal);
                        }
                    } else {
                        $el.val(value);
                        if ($el.hasClass('selectpicker')) {
                            $el.selectpicker('val', value);
                        }
                    }
                }
            }
            return hasParams;
        }

    })
</script>
