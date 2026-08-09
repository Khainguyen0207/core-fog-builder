<?php

namespace Modules\Shared\Tables\Factory;

use Illuminate\Contracts\Container\Container;
use Modules\Shared\Tables\Registry\TableRegistry;
use Modules\Shared\Tables\Table;

class TableFactory
{
    public function __construct(
        private readonly TableRegistry $registry,
        private readonly Container $container,
    ) {}

    public function make(string $key): Table
    {
        return $this->container->make($this->registry->resolve($key));
    }
}
