<?php

namespace Modules\Shared\Tables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Str;
use InvalidArgumentException;
use LogicException;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Contracts\TableName;
use Yajra\DataTables\Facades\DataTables;

abstract class Table implements TableName
{
    /** @var array<int, object> */
    private array $columns = [];

    /** @var array<int, object> */
    private array $operations = [];

    public array $filters = [];

    public string $template = 'shared::tables.page';

    public EloquentBuilder|QueryBuilder|null $dataTables = null;

    public string $route = '';

    public array $headerActions = [];

    public ?Model $model = null;

    private EloquentBuilder|QueryBuilder|null $query = null;

    protected bool $hasOperationsColumn = true;

    protected bool $hasFilter = false;

    protected bool $hasCheckBox = true;

    protected bool $hasHeaderAction = true;

    protected bool $hasBulkDelete = false;

    protected string $name = 'Base Table';

    protected string $nameTable = '';

    public static function __callStatic(string $method, array $arguments): mixed
    {
        return static::make()->{$method}(...$arguments);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function setup(): static
    {
        $this->name = 'Base Table';
        $this->nameTable = '';
        $this->columns = [];
        $this->operations = [];
        $this->filters = [];
        $this->headerActions = [];
        $this->template = 'shared::tables.page';
        $this->route = '';
        $this->model = null;
        $this->query = null;
        $this->hasOperationsColumn = true;
        $this->hasFilter = false;
        $this->hasCheckBox = true;
        $this->hasHeaderAction = true;
        $this->hasBulkDelete = false;

        return $this;
    }

    public function isHasFilter(): bool
    {
        return $this->hasFilter;
    }

    public function hasFilter(bool $hasFilter = true): static
    {
        $this->hasFilter = $hasFilter;

        return $this;
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
        return $this->hasBulkDelete(false);
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

    public function setRoute(string $route): static
    {
        $this->route = Str::beforeLast($route, '.').'.';

        return $this;
    }

    /** @param class-string<Model> $class */
    public function setModel(string $class): static
    {
        $this->model = new $class;

        return $this;
    }

    protected function operationsColumn(bool $hasOperationsColumn = true): static
    {
        $this->hasOperationsColumn = $hasOperationsColumn;

        return $this;
    }

    public function hasOperationsColumn(): bool
    {
        return $this->hasOperationsColumn;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getColumnsToJson(): string
    {
        $columns = [];

        if ($this->hasCheckBox) {
            $columns[] = [
                'data' => 'id',
                'orderable' => false,
                'searchable' => false,
                'render' => '__ROW_CHECKBOX_RENDER__',
            ];
        }

        foreach ($this->columns as $column) {
            $columns[] = ['data' => $column->getName()];
        }

        if ($this->hasOperationsColumn) {
            $columns[] = [
                'data' => 'operations',
                'orderable' => false,
                'searchable' => false,
                'defaultContent' => '',
            ];
        }

        return json_encode($columns, JSON_THROW_ON_ERROR);
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

    public function getName(): string
    {
        return $this->name;
    }

    protected function addColumn(string $key, string|array $value): static
    {
        $this->columns[$key] = $value;

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

    protected function usingQuery(EloquentBuilder|QueryBuilder $builder): static
    {
        $this->query = $builder;

        return $this;
    }

    public function getUsingQuery(): EloquentBuilder|QueryBuilder
    {
        return $this->query !== null ? clone $this->query : $this->baseQuery();
    }

    public function renderTable(): View
    {
        $this->setup();

        if (trim($this->template) === '') {
            throw new InvalidArgumentException('Table view must not be empty.');
        }

        if (! ViewFacade::exists($this->template)) {
            throw new InvalidArgumentException("View [{$this->template}] not found.");
        }

        return view($this->template, [
            'table' => $this,
            'title' => $this->getNameTable() !== '' ? $this->getNameTable() : 'Example App',
            'name' => $this->getName(),
            'data' => '',
            'dataTables' => $this->getModel() ?? [],
        ]);
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

    protected function addOperations(array $operations): static
    {
        $this->operations = $operations;

        return $this;
    }

    public function getType(): string
    {
        return static::class;
    }

    public function getDataTable(): JsonResponse
    {
        $columns = $this->getColumns();
        $columnNames = array_map(fn (object $column): string => $column->getName(), $columns);
        $formatColumns = array_values(array_filter(
            $columns,
            fn (object $column): bool => $column instanceof FormatColumn,
        ));
        $rawColumns = array_map(
            fn (FormatColumn $column): string => $column->getName(),
            $formatColumns,
        );

        $dataTable = DataTables::of($this->getUsingQuery())->filter(function ($query) use ($columnNames): void {
            $filterNames = array_map(
                fn (object $filter): string => $filter->getName(),
                $this->getFilters(),
            );
            $modelColumns = $this->model?->getFillable() ?? [];
            $filterable = array_values(array_unique(array_merge($columnNames, $filterNames, $modelColumns)));
            $dataSearch = $this->dataSearch();

            foreach ($filterable as $column) {
                $value = $dataSearch[$column] ?? null;

                if ($value === null || $value === '') {
                    continue;
                }

                if (str_contains($column, '.') && $query instanceof EloquentBuilder) {
                    [$relation, $relationColumn] = explode('.', $column, 2);
                    $query->whereHas($relation, function (EloquentBuilder $relationQuery) use ($relationColumn, $value): void {
                        $relationQuery->where($relationColumn, 'like', "%{$value}%");
                    });

                    continue;
                }

                if (! str_contains($column, '.')) {
                    $query->where($column, 'like', "%{$value}%");
                }
            }

            $rangeColumns = [];
            foreach ($this->getFilters() as $filter) {
                if ($filter->getFilterType() === 'range') {
                    $rangeColumns[] = Str::beforeLast($filter->getName(), '_from');
                }
            }

            foreach (array_unique($rangeColumns) as $column) {
                if (! in_array($column, $filterable, true)) {
                    continue;
                }

                $from = request()->input($column.'_from');
                $to = request()->input($column.'_to');

                if ($from !== null && $from !== '') {
                    $query->where($column, '>=', $from);
                }

                if ($to !== null && $to !== '') {
                    $query->where($column, '<=', $to);
                }
            }
        });

        if ($this->hasOperationsColumn) {
            $dataTable->addColumn('operations', fn (mixed $item): string => $this->renderOperations($item));
            $rawColumns[] = 'operations';
        }

        foreach ($formatColumns as $column) {
            $callbacks = $column->getValueUsingCallbacks;
            if ($callbacks === []) {
                continue;
            }

            $dataTable->editColumn($column->getName(), function (mixed $item) use ($column, $callbacks): mixed {
                $context = $column->setItem($item);
                $value = null;
                $hasValue = false;

                foreach ($callbacks as $callback) {
                    $value = $hasValue ? $callback($context, $value) : $callback($context);
                    $hasValue = true;
                }

                return $value;
            });
        }

        $this->rememberPreviousUrl();

        return $dataTable->rawColumns($rawColumns)->toJson();
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

    private function baseQuery(): EloquentBuilder
    {
        $model = $this->model ?? throw new LogicException('A model or query must be configured for the table.');
        $columns = array_values(array_filter(
            array_map(fn (object $column): string => $column->getName(), $this->columns),
            fn (string $column): bool => ! str_contains($column, '.'),
        ));
        $query = $model->newQuery();

        return $columns !== [] ? $query->select($columns) : $query;
    }

    private function dataSearch(): array
    {
        $search = request()->input('search.value');

        if (! is_string($search) || $search === '') {
            return [];
        }

        $decoded = json_decode($search, true);

        return is_array($decoded) && isset($decoded['dataSearch']) && is_array($decoded['dataSearch'])
            ? $decoded['dataSearch']
            : [];
    }

    private function rememberPreviousUrl(): void
    {
        if ($this->route === '' || ! Route::has($this->route.'index')) {
            return;
        }

        $sessionKey = (string) config(
            'figure-admin-shared.table.previous_url_session_prefix',
            'previous_table_url',
        );

        session()->put($sessionKey, route($this->route.'index', $this->dataSearch()));
    }

    private function renderOperations(mixed $item): string
    {
        $html = '';

        foreach ($this->operations as $operation) {
            $key = $operation->getAttributes()['key'] ?? null;
            $id = $key !== null && $key !== ''
                ? data_get($item, $key)
                : ($item instanceof Model ? $item->getKey() : data_get($item, 'id'));

            if ($id === null) {
                throw new LogicException('The operation key could not be resolved from the table row.');
            }

            $html .= view($operation->getTemplate(), [
                'operation' => $operation,
                'id' => $id,
                'tableName' => $this->getName(),
            ])->render();
        }

        return $html;
    }
}
