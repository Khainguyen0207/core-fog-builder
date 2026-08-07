<?php

namespace App\Forms\Fields;

abstract class BaseField
{
    protected string $name;

    private array $attributes = [
        'class' => 'form-control',
        'autocomplete' => 'on',
    ];

    private string $label;

    private bool $filter = false;

    private string $filterType = 'default';

    protected string $type;

    public bool $required = false;

    public bool $readonly = false;

    public bool $disabled = false;

    private int $span = 1;

    protected ?string $defaultValue = '';

    public function setDefaultValue(string $defaultValue): static
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    public static function make(string $name): static
    {
        $obj = app(static::class);

        return $obj->setName($name);
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

    public function setSpan(int $span): static
    {
        $this->span = $span;

        return $this;
    }

    public function getSpan(): int
    {
        return $this->span;
    }

    public function getName(): string
    {
        return $this->name;
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

    public function setAttributes(array $attrs): static
    {
        $this->attributes = array_merge($this->attributes, $attrs);

        return $this;
    }

    public function getAllAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $attr): ?string
    {
        return $this->attributes[$attr] ?? null;
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
        $this->setAttributes([
            'helper_text' => $helperText,
        ]);

        return $this;
    }

    public function getViewPath(): string
    {
        return $this->viewPath;
    }
}
