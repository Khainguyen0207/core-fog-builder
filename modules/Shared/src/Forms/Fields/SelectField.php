<?php

namespace Modules\Shared\Forms\Fields;

class SelectField extends BaseField
{
    protected string $type = 'select';

    protected string $viewPath = 'shared::forms.fields.select';

    protected bool $multiple = false;

    private array $options = [];

    private array $values = [];

    public function getOptions(): array
    {
        return $this->options;
    }

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

    public function setValue(array $value): static
    {
        $this->values = $value;

        return $this;
    }

    public function getValue(): array
    {
        return $this->values;
    }
}
