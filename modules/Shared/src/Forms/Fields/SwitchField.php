<?php

namespace Modules\Shared\Forms\Fields;

class SwitchField extends BaseField
{
    protected string $type = 'switch';

    protected string $viewPath = 'shared::forms.fields.switch';

    protected mixed $defaultValue = '0';

    public function setPlaceholder(string $placeholder): static
    {
        return $this->setAttributes(['placeholder' => $placeholder]);
    }
}
