<?php

namespace Modules\Shared\Tables\Registry;

use InvalidArgumentException;
use LogicException;
use Modules\Shared\Tables\Table;

class TableRegistry
{
    /** @var array<string, class-string<Table>> */
    private array $tables = [];

    /** @param class-string<Table> $class */
    public function register(string $key, string $class): void
    {
        if (! is_subclass_of($class, Table::class)) {
            throw new InvalidArgumentException("{$class} must extend ".Table::class.'.');
        }

        if (isset($this->tables[$key])) {
            if ($this->tables[$key] === $class) {
                return;
            }

            throw new LogicException("Table key [{$key}] is already registered to [{$this->tables[$key]}].");
        }

        $this->tables[$key] = $class;
    }

    /** @return class-string<Table> */
    public function resolve(string $key): string
    {
        return $this->tables[$key]
            ?? throw new InvalidArgumentException("Unknown table key: {$key}");
    }

    /** @return array<string, class-string<Table>> */
    public function all(): array
    {
        return $this->tables;
    }
}
