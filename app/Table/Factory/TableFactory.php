<?php

namespace App\Table\Factory;

use App\Table\BaseTable;
use App\Table\Configs\TableConfig;

class TableFactory
{
    public function __construct(private TableConfig $config) {}

    public function make(string $key): BaseTable
    {
        $class = $this->config->resolve($key);

        return app($class);
    }
}
