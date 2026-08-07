<?php

namespace App\Table\Columns;

use Closure;

class FormatColumn extends BaseColumn
{
    public array $getValueUsingCallbacks = [];

    public mixed $item = null;

    public function getValueUsing(Closure $callback): static
    {
        $this->getValueUsingCallbacks[] = $callback;

        return $this;
    }

    public function setItem($item): FormatColumn
    {
        $this->item = $item;

        return $this;
    }

    public function getItem()
    {
        return $this->item;
    }
}
