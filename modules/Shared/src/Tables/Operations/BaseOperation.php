<?php

namespace Modules\Shared\Tables\Operations;

abstract class BaseOperation
{
    private string $name = '';

    private string $description = '';

    private string $method = 'DELETE';

    private array $attributes = [
        'class' => 'btn btn-icon btn-sm btn-danger text-white mb-1',
    ];

    private string $actionUrl = '';

    private string $dataActionUrl = '';

    private bool $isModal = true;

    private string $icon = 'bx bx-edit';

    private string $template = 'shared::tables.operations.base';

    private string $viewModal = '';

    private string $nameViewModal = '';

    public static function __callStatic(string $method, array $arguments): mixed
    {
        return static::make()->{$method}(...$arguments);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function setViewModal(string $viewModal): static
    {
        $this->viewModal = $viewModal;

        return $this;
    }

    public function getViewModal(): string
    {
        return $this->viewModal;
    }

    public function setNameViewModal(string $nameViewModal): static
    {
        $this->nameViewModal = $nameViewModal;

        return $this;
    }

    public function getNameViewModal(): string
    {
        return $this->nameViewModal;
    }

    public function isHasModal(): bool
    {
        return $this->isModal;
    }

    public function hasModal(bool $hasModal = true): static
    {
        $this->isModal = $hasModal;

        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setDataActionUrl(string $dataActionUrl): static
    {
        $this->dataActionUrl = $dataActionUrl;

        return $this;
    }

    public function getDataActionUrl(): string
    {
        return $this->dataActionUrl;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
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

    public function setActionUrl(string $actionUrl): static
    {
        $this->actionUrl = $actionUrl;

        return $this;
    }

    public function getActionUrl(): string
    {
        return $this->actionUrl;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function setAttribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }
}
