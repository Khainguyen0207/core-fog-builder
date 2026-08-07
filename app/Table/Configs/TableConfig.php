<?php

namespace App\Table\Configs;

use App\Table\BaseTable;
use InvalidArgumentException;

class TableConfig
{
    private array $map = [];

    public function register(string $key, string $class): void
    {
        if (! is_subclass_of($class, BaseTable::class)) {
            throw new InvalidArgumentException("{$class} must extend BaseTable");
        }

        $this->map[$key] = $class;
    }

    public function resolve(string $key): string
    {
        if (! isset($this->map[$key])) {
            throw new InvalidArgumentException("Unknown table key: {$key}");
        }

        return $this->map[$key];
    }

    public function all(): array
    {
        return $this->map;
    }
}
