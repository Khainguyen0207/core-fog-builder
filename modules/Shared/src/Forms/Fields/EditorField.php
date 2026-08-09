<?php

namespace Modules\Shared\Forms\Fields;

class EditorField extends BaseField
{
    protected string $type = 'editor';

    protected string $viewPath = 'shared::forms.fields.editor';

    private array $options = [];

    public function __construct()
    {
        $this->setSpan(2);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }
}
