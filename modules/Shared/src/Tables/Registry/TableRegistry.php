<?php

namespace Modules\Shared\Tables\Registry;

use InvalidArgumentException;
use LogicException;
use Modules\Shared\Registry\Contracts\RegistrationVisibility;
use Modules\Shared\Tables\Table;

class TableRegistry
{
    /** @var array<string, array{class: class-string<Table>, owner: string|null}> */
    private array $tables = [];

    public function __construct(private readonly ?RegistrationVisibility $visibility = null) {}

    /** @param class-string<Table> $class */
    public function register(string $key, string $class, ?string $owner = null): void
    {
        if (! is_subclass_of($class, Table::class)) {
            throw new InvalidArgumentException("{$class} must extend ".Table::class.'.');
        }

        if (isset($this->tables[$key])) {
            if ($this->tables[$key]['class'] === $class && $this->tables[$key]['owner'] === $owner) {
                return;
            }

            throw new LogicException("Table key [{$key}] is already registered to [{$this->tables[$key]['class']}].");
        }

        $this->tables[$key] = ['class' => $class, 'owner' => $owner];
    }

    /** @return class-string<Table> */
    public function resolve(string $key): string
    {
        $entry = $this->tables[$key] ?? null;

        if ($entry === null || ! $this->isVisible($entry['owner'])) {
            throw new InvalidArgumentException("Unknown table key: {$key}");
        }

        return $entry['class'];
    }

    /** @return array<string, class-string<Table>> */
    public function all(): array
    {
        $tables = [];

        foreach ($this->tables as $key => $entry) {
            if ($this->isVisible($entry['owner'])) {
                $tables[$key] = $entry['class'];
            }
        }

        return $tables;
    }

    private function isVisible(?string $owner): bool
    {
        return $owner === null || ($this->visibility?->allows($owner) ?? true);
    }
}
