<?php

namespace Modules\Shared\Tables\Operations;

class BasicOperation extends BaseOperation
{
    public function __construct()
    {
        $this->hasModal(false);
    }
}
