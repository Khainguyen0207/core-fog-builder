<?php

namespace App\Forms\Fields;

class SelectField extends BaseField
{
    protected string $type = 'select';

    protected bool $filterDom = false;

    protected string $viewPath = 'admin.components.fields.select';

    protected bool $multiple = false;

    private array $options = [];

    public function getOptions(): array
    {
        return $this->options;
    }

    protected array $arrayValues = [];

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function hasMultiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function setValue($value): static
    {
        $this->arrayValues = $value;

        return $this;
    }

    public function getValue(): array
    {
        return $this->arrayValues;
    }
}
