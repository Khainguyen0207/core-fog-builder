<?php

namespace App\Forms\Fields;

class InputField extends BaseField
{
    protected string $type = 'text';

    protected string $viewPath = 'admin.components.fields.input';

    public bool $multiple = false;

    public bool $preview = false;

    public string $accept = 'images/*';

    public function setPlaceholder(string $placeholder): static
    {
        $this->setAttributes([
            'placeholder' => $placeholder,
        ]);

        return $this;
    }

    public function isMultiple($multiple = true): static
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

    public function isPreview($isPreview = true): static
    {
        $this->preview = $isPreview;

        return $this;
    }
}
