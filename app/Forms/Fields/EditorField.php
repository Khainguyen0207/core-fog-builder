<?php

namespace App\Forms\Fields;

class EditorField extends BaseField
{
    protected string $type = 'editor';

    protected string $viewPath = 'admin.components.fields.editor';

    private array $options = [];

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function __construct()
    {
        $this->setSpan(2);
    }
}
