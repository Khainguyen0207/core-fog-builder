<?php

namespace Modules\Shared\Forms\Fields;

abstract class BaseField
{
    protected string $name = '';

    protected string $type = '';

    protected string $viewPath = '';

    protected mixed $defaultValue = '';

    private array $attributes = [
        'class' => 'form-control',
        'autocomplete' => 'on',
    ];

    private string $label = '';

    private bool $filter = false;

    private string $filterType = 'default';

    public bool $required = false;

    public bool $readonly = false;

    public bool $disabled = false;

    private int $span = 1;

    public static function make(string $name): static
    {
        return app(static::class)->setName($name);
    }

    public function setDefaultValue(mixed $defaultValue): static
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function isFilter(): bool
    {
        return $this->filter;
    }

    public function getFilterType(): string
    {
        return $this->filterType;
    }

    public function hasFilter(bool $filter = true, string $type = 'default'): static
    {
        $this->filter = $filter;
        $this->filterType = $type;

        return $this;
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

    public function setSpan(int $span): static
    {
        $this->span = $span;

        return $this;
    }

    public function getSpan(): int
    {
        return $this->span;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function getAllAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $attribute): mixed
    {
        return $this->attributes[$attribute] ?? null;
    }

    public function isRequired(): static
    {
        $this->required = true;

        return $this;
    }

    public function isDisabled(): static
    {
        $this->disabled = true;

        return $this;
    }

    public function isReadonly(): static
    {
        $this->readonly = true;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function helperText(string $helperText): static
    {
        return $this->setAttributes(['helper_text' => $helperText]);
    }

    public function getViewPath(): string
    {
        return $this->viewPath;
    }
}
