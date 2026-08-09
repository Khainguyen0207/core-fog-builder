<?php

namespace Modules\Shared\Tables\Columns;

abstract class BaseColumn
{
    private array $attributes = [];

    protected string $name = '';

    protected string $label = '';

    protected mixed $defaultValue = null;

    public static function make(?string $name = null): static
    {
        return app(static::class)->setup($name);
    }

    protected function setup(?string $name = null): static
    {
        if ($name !== null) {
            $this->name = $name;
        }

        $this->attributes = [
            'name' => $this->name,
            'label' => $this->getLabel(),
        ];

        return $this;
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getAttributes(array $attributes = []): array|static
    {
        if ($attributes === []) {
            return $this->attributes;
        }

        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
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

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label !== '' ? $this->label : $this->name;
    }
}
