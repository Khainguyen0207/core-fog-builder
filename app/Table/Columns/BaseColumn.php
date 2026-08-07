<?php

namespace App\Table\Columns;

abstract class BaseColumn
{
    private array $attributes = [];

    protected string $name;

    protected string $label;

    protected ?string $defaultValue = null;

    protected function setup(?string $name = null): static
    {
        if ($name) {
            $this->name = $name;
        }

        $this->attributes = [
            'name' => $this->name,
            'label' => $this->label ?? $this->name,
        ];

        return $this;
    }

    public static function make(?string $name = null): static
    {
        return app(static::class)->setup($name);
    }

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getAttributes(array $attrs): static
    {
        $this->attributes = array_merge($this->attributes, $attrs);

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
        return $this->label ?? $this->name;
    }
}
