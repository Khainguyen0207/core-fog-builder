<?php

namespace Modules\Shared\Forms\Fields;

class InputField extends BaseField
{
    protected string $type = 'text';

    protected string $viewPath = 'shared::forms.fields.input';

    public bool $multiple = false;

    public bool $preview = false;

    public string $accept = 'images/*';

    public function setPlaceholder(string $placeholder): static
    {
        return $this->setAttributes(['placeholder' => $placeholder]);
    }

    public function isMultiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getAccept(): string
    {
        return $this->accept;
    }

    public function setAccept(string $accept): static
    {
        $this->accept = $accept;

        return $this;
    }

    public function isPreview(bool $preview = true): static
    {
        $this->preview = $preview;

        return $this;
    }
}
