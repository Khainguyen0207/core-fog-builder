<?php

namespace Modules\Shared\Forms;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use LogicException;
use Modules\Shared\Forms\Fields\BaseField;

abstract class Form
{
    private array $fields = [];

    protected bool $hasFile = false;

    protected string $view = 'shared::forms.form';

    protected string $title = '';

    private ?Model $model = null;

    private string $template = 'shared::forms.page';

    private ?Route $route = null;

    private ?string $action = null;

    private ?string $method = null;

    private ?string $cancelUrl = null;

    public static function __callStatic(string $method, array $arguments): mixed
    {
        return static::make()->{$method}(...$arguments);
    }

    public static function make(): static
    {
        return app(static::class)->setup();
    }

    public function setup(): static
    {
        $this->fields = [];
        $this->view = 'shared::forms.form';
        $this->template = 'shared::forms.page';
        $this->route = RouteFacade::getCurrentRoute();

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $name): ?BaseField
    {
        foreach ($this->fields as $field) {
            if ($field->getName() === $name) {
                return $field;
            }
        }

        return null;
    }

    public function setRoute(?Route $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function hasFile(?bool $hasFile = null): static|bool
    {
        if ($hasFile === null) {
            return $this->hasFile;
        }

        $this->hasFile = $hasFile;

        return $this;
    }

    public function setHasFile(bool $hasFile = true): static
    {
        $this->hasFile = $hasFile;

        return $this;
    }

    public function action(?string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): ?string
    {
        if ($this->action !== null) {
            return $this->action;
        }

        $routeBase = $this->routeBase();
        if ($routeBase === null || $this->model === null) {
            return null;
        }

        return $this->model->getKey()
            ? route($routeBase.'.update', $this->model->getKey())
            : route($routeBase.'.store');
    }

    public function method(?string $method): static
    {
        $this->method = $method !== null ? strtoupper($method) : null;

        return $this;
    }

    public function getMethod(): string
    {
        if ($this->method !== null) {
            return $this->method;
        }

        return $this->model?->getKey() ? 'PUT' : 'POST';
    }

    public function cancelUrl(?string $cancelUrl): static
    {
        $this->cancelUrl = $cancelUrl;

        return $this;
    }

    public function getCancelUrl(): ?string
    {
        if ($this->cancelUrl !== null) {
            return $this->cancelUrl;
        }

        $sessionKey = (string) config(
            'figure-admin-shared.table.previous_url_session_prefix',
            'previous_table_url',
        );
        $previousUrl = session()->get($sessionKey);

        if (is_string($previousUrl) && $previousUrl !== '') {
            return $previousUrl;
        }

        $routeBase = $this->routeBase();

        return $routeBase !== null && RouteFacade::has($routeBase.'.index')
            ? route($routeBase.'.index')
            : null;
    }

    public function add(string $name, string $type, BaseField $field): static
    {
        $this->fields[] = $field;

        return $this;
    }

    public function addMore(array $fields): static
    {
        foreach ($fields as $field) {
            $this->add($field['name'], $field['type'], $field['field']);
        }

        return $this;
    }

    public function renderForm(): View|Factory
    {
        return view($this->template, $this->getDataForm());
    }

    public function getDataForm(): array
    {
        $identity = Str::slug(class_basename(static::class));
        $modelKey = $this->model?->getKey();
        $id = $identity.($modelKey !== null ? '-'.$modelKey : '-new');

        return [
            'id' => $id,
            'class' => $identity,
            'form' => $this,
        ];
    }

    public function createWithModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): Model
    {
        return $this->model ?? throw new LogicException('A model must be configured before rendering the form.');
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function model(string $model, ?Model $hasModel = null): static
    {
        $this->model = $hasModel ?? new $model;

        return $this;
    }

    public function setView(string $view): static
    {
        $this->view = $view;

        return $this;
    }

    private function routeBase(): ?string
    {
        $routeName = $this->route?->getName();

        return is_string($routeName) && $routeName !== ''
            ? Str::beforeLast($routeName, '.')
            : null;
    }
}
