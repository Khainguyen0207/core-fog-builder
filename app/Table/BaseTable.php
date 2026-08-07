<?php

namespace App\Table;

use App\Table\Columns\FormatColumn;
use App\Table\Configs\TableNameConfig;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Facades\DataTables;

abstract class BaseTable implements TableNameConfig
{
    private array $columns = [];

    private array $operations = [];

    public array $filters = [];

    public string $template = 'components.tables.base-table';

    public EloquentBuilder|Builder|null $dataTables = null;

    public string $route = '';

    public array $headerActions = [];

    public ?Model $model = null;

    private ?EloquentBuilder $query = null;

    protected bool $hasOperationsColumn = true;

    protected bool $hasFilter = false;

    protected bool $hasCheckBox = true;

    protected bool $hasHeaderAction = true;

    protected bool $hasBulkDelete = true;

    protected string $name;

    protected string $nameTable;

    public function isHasFilter(): bool
    {
        return $this->hasFilter;
    }

    public function isHasCheckBox(): bool
    {
        return $this->hasCheckBox;
    }

    public function hasCheckbox(bool $hasCheckBox = true): static
    {
        $this->hasCheckBox = $hasCheckBox;

        return $this;
    }

    public function hasBulkDelete(bool $hasBulkDelete = true): static
    {
        $this->hasBulkDelete = $hasBulkDelete;

        return $this;
    }

    public function notBulkDelete(): static
    {
        $this->hasBulkDelete = false;

        return $this;
    }

    public function isHasBulkDelete(): bool
    {
        return $this->hasBulkDelete;
    }

    public function notHeaderAction(): static
    {
        $this->hasHeaderAction = false;

        return $this;
    }

    public function hasHeaderAction(): bool
    {
        return $this->hasHeaderAction;
    }

    public function hasFilter(bool $hasFilter = true): static
    {
        $this->hasFilter = $hasFilter;

        return $this;
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::make();

        return $instance->$method(...$arguments);
    }

    public function setup(): static
    {
        $this->name = 'Base Table';
        $this->headerActions = [];
        $this->columns = [];
        $this->template = 'tables.base';
        $this->route = '';

        return $this;
    }

    public function setRoute(string $route): static
    {
        $this->route = Str::beforeLast($route, '.') . '.';

        return $this;
    }

    public function setModel(string $class): static
    {
        $this->model = new $class;

        return $this;
    }

    public static function make()
    {
        return app(static::class);
    }

    protected function operationsColumn(bool $hasOperationsColumn = true): BaseTable|static
    {
        $this->hasOperationsColumn = $hasOperationsColumn;

        return $this;
    }

    public function hasOperationsColumn(): bool
    {
        return $this->hasOperationsColumn;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getColumnsToJson(): false|string
    {
        $columns = [];
        if ($this->hasCheckBox) {
            $columns[0] = [
                'data' => 'id',
                'orderable' => false,
                'searchable' => false,
                'render' => '__ROW_CHECKBOX_RENDER__',
            ];
        }

        foreach ($this->columns as $column) {
            $columns[] = [
                'data' => $column->getName(),
            ];
        }

        if ($this->hasOperationsColumn) {
            $view = '';

            foreach ($this->operations as $operation) {
                $view .= view($operation->getTemplate(), [
                    'operation' => $operation,
                    'id' => 0,
                ])->render();
            }

            $columns[] = [
                'data' => null,
                'orderable' => false,
                'searchable' => false,
                'defaultContent' => $view,
            ];
        }

        return json_encode($columns, true);
    }

    public function headerActions(array $actions): static
    {
        $this->headerActions = $actions;

        return $this;
    }

    public function getHeaderActions(): array
    {
        return $this->headerActions;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function addColumn(string $key, string|array $value): static
    {
        $this->columns = array_merge($this->columns, [$key => $value]);

        return $this;
    }

    protected function addColumns(array $columns): static
    {
        foreach ($columns as $column) {
            $this->columns[] = $column;
        }

        return $this;
    }

    protected function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    private function baseQuery(): EloquentBuilder
    {
        $fill = [];

        foreach ($this->columns as $column) {
            $fill[] = $column->getName();
        }

        return $this->model->newQuery()->select($fill);
    }

    protected function usingQuery(Builder|EloquentBuilder $builder): static
    {
        $this->query = $builder;

        return $this;
    }

    public function getUsingQuery(): EloquentBuilder
    {
        return ! empty($this->query) ? $this->query->newQuery() : $this->baseQuery();
    }

    private function render(): array
    {
        if (! trim($this->template)) {
            throw new \InvalidArgumentException('View not empty.');
        }

        if (\Illuminate\Support\Facades\View::exists($this->template)) {
            throw new \InvalidArgumentException("View [$this->template] not found.");
        }

        $this->setup();

        return [
            'table' => $this,
            'title' => $this->getNameTable() ?? 'Example App',
            'name' => $this->getName() ?? throw new Exception("Table [$this->nameTable] not found."),
            'data' => '',
            'dataTables' => $this->getModel() ?? [],
        ];
    }

    public function renderTable(): View|Application|Factory|\Illuminate\View\View
    {
        $view = 'admin.' . $this->template;

        return view($view, $this->render());
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    protected function addFilters(array $filters): static
    {
        $this->filters = $filters;

        return $this;
    }

    protected function addFilter(mixed $filter): static
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    protected function addOperation(mixed $operation): static
    {
        $this->operations[] = $operation;

        return $this;
    }

    protected function addOperations(array $operation): static
    {
        $this->operations = $operation;

        return $this;
    }

    public function getType(): string
    {
        return static::class;
    }

    public function getDataTable(): JsonResponse
    {
        $htmlColumn = [];
        $columnNames = [];
        $columns = $this->getColumns();

        foreach ($columns as $column) {
            $columnNames[] = $column->getName();

            if ($column instanceof FormatColumn) {
                $htmlColumn[] = $column->getName();
            }
        }
        $query = $this->getUsingQuery();
        $queryBuilder = null;

        $formatColumns = array_values(array_filter($columns, fn($c) => $c instanceof FormatColumn));

        $dataTable = DataTables::eloquent($query)->filter(function ($query) use ($columnNames, $queryBuilder) {
            $ranges = array_values(array_filter($this->getFilters(), fn($filter) => $filter->getFilterType() === 'range'));

            $filterNames = array_map(fn($f) => $f->getName(), $this->getFilters());
            $modelColumns = $this->model ? $this->model->getFillable() : [];
            $allFilterable = array_unique(array_merge($columnNames, $filterNames, $modelColumns));
            $search = json_decode(request()->input('search.value'), true);

            if (! $search['dataSearch']) {
                return $query;
            } else {
                $dataSearch = $search['dataSearch'];

                foreach ($allFilterable as $column) {
                    $value = $dataSearch[$column] ?? '';

                    if (! $value) {
                        continue;
                    }

                    if (str_contains($column, '.')) {
                        [$relation, $relationCol] = explode('.', $column, 2);

                        $query->whereHas($relation, function ($q) use ($relationCol, $value) {
                            $q->where($relationCol, 'like', "%{$value}%");
                        });
                    } else {

                        $query->where($column, 'like', "%{$value}%");
                    }
                }
            }

            foreach ($allFilterable as $col) {
                $value = request()->input($col);

                foreach ($ranges as $range) {
                    $key = Str::beforeLast($range->getName(), '_from');
                    $from = request()->input($key . '_from');
                    $to = request()->input($key . '_to');

                    if ($from && in_array($key, $allFilterable)) {
                        $query->where($key, '>=', $from);
                    }

                    if ($to && in_array($key, $allFilterable)) {
                        $query->where($key, '<=', $to);
                    }
                }
            }

            if ($query->getModel()->getTable() === Auth::user()->getTable()) {
                $query->whereNot('id', Auth::user()->getKey());
            }

            return $query;
        });

        foreach ($formatColumns as $col) {
            $callbacks = $col->getValueUsingCallbacks ?? [];
            if (! $callbacks) {
                continue;
            }

            $dataTable->editColumn($col->getName(), function ($item) use ($col, $callbacks) {
                $ctx = $col->setItem($item);

                $value = null;
                $hasValue = false;

                foreach ($callbacks as $cb) {
                    $value = $hasValue ? $cb($ctx, $value) : $cb($ctx);
                    $hasValue = true;
                }

                return $value;
            });
        }

        if ($this->route && \Illuminate\Support\Facades\Route::has($this->route . 'index')) {
            $search = json_decode(request()->input('search.value'), true);
            $params = is_array($search) && isset($search['dataSearch']) && is_array($search['dataSearch'])
                ? $search['dataSearch']
                : [];

            session()->put('previous_table_url', route($this->route . 'index', $params));
        }

        return $dataTable->rawColumns($htmlColumn)->toJson();
    }

    public function getNameTable(): string
    {
        return $this->nameTable;
    }

    public function setNameTable(string $name): static
    {
        $this->nameTable = $name;

        return $this;
    }
}
