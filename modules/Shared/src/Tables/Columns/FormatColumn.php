<?php

namespace Modules\Shared\Tables\Columns;

use Closure;

class FormatColumn extends BaseColumn
{
    /** @var array<int, Closure> */
    public array $getValueUsingCallbacks = [];

    public mixed $item = null;

    public function getValueUsing(Closure $callback): static
    {
        $this->getValueUsingCallbacks[] = $callback;

        return $this;
    }

    public function setItem(mixed $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getItem(): mixed
    {
        return $this->item;
    }
}
