<?php

namespace Modules\Shared\BulkActions;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use LogicException;
use Modules\Shared\BulkActions\Contracts\BulkDeleteHandler;

class BulkDeleteRegistry
{
    /** @var array<string, class-string<BulkDeleteHandler>> */
    private array $handlers = [];

    public function __construct(private readonly Container $container) {}

    /** @param class-string<BulkDeleteHandler> $handler */
    public function register(string $resource, string $handler): void
    {
        if (trim($resource) === '') {
            throw new InvalidArgumentException('Bulk delete resource key must not be empty.');
        }

        if (! is_subclass_of($handler, BulkDeleteHandler::class)) {
            throw new InvalidArgumentException("{$handler} must implement ".BulkDeleteHandler::class.'.');
        }

        if (isset($this->handlers[$resource])) {
            if ($this->handlers[$resource] === $handler) {
                return;
            }

            throw new LogicException("Bulk delete resource [{$resource}] is already registered to [{$this->handlers[$resource]}].");
        }

        $this->handlers[$resource] = $handler;
    }

    public function has(string $resource): bool
    {
        return isset($this->handlers[$resource]);
    }

    public function resolve(string $resource): ?BulkDeleteHandler
    {
        $handler = $this->handlers[$resource] ?? null;

        return $handler === null ? null : $this->container->make($handler);
    }

    /** @return array<string, class-string<BulkDeleteHandler>> */
    public function all(): array
    {
        return $this->handlers;
    }
}
