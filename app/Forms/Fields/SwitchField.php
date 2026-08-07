<?php

namespace App\Forms\Fields;

class SwitchField extends BaseField
{
    protected string $type = 'switch';

    protected string $viewPath = 'admin.components.fields.switch';

    protected ?string $defaultValue = '0';

    public function setPlaceholder(string $placeholder): static
    {
        $this->setAttributes([
            'placeholder' => $placeholder,
        ]);

        return $this;
    }
}
