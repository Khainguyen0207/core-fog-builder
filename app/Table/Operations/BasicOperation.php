<?php

namespace App\Table\Operations;

class BasicOperation extends BaseOperation
{
    public function __construct()
    {
        return $this
            ->hasModal(false);
    }
}
