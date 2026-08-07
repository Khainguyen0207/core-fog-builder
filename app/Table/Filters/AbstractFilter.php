<?php

namespace App\Table\Filters;

abstract class AbstractFilter
{
    private array $fields = [];

    private string $type = '';

    public function setFields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
