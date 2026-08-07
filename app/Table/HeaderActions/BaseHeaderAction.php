<?php

namespace App\Table\HeaderActions;

abstract class BaseHeaderAction
{
    private string $name;

    private string $label = 'Button';

    private string $description = '';

    private string $method = 'DELETE';

    private array $attributes = [
        'class' => 'btn btn-icon btn-sm btn-danger text-white',
    ];

    private string $actionUrl = '';

    private string $dataActionUrl = '';

    private bool $isModal = true;

    private string $icon = 'bx bx-edit';

    private string $template = 'admin.components.header-actions.base';

    private string $viewModal = '';

    private string $nameViewModal = '';

    public static function __callStatic($method, $arguments)
    {
        $instance = static::make();

        return $instance->$method(...$arguments);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setViewModal(string $viewModal): static
    {
        $this->viewModal = $viewModal;

        return $this;
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

    public function getViewModal(): string
    {
        return $this->viewModal;
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

    public function setMethod(string $method): static
    {
        $this->method = $method;

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

    public static function make()
    {
        return app(static::class);
    }

    public function getName(): string
    {
        return $this->name;
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

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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
        $this->attributes = array_merge($this->attributes, [
            $key => $value,
        ]);

        return $this;
    }
}
