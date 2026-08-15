<?php

namespace Modules\Shared\BulkActions;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use LogicException;
use Modules\Shared\BulkActions\Contracts\BulkDeleteHandler;
use Modules\Shared\Registry\Contracts\RegistrationVisibility;

class BulkDeleteRegistry
{
    /** @var array<string, array{handler: class-string<BulkDeleteHandler>, owner: string|null}> */
    private array $handlers = [];

    public function __construct(
        private readonly Container $container,
        private readonly ?RegistrationVisibility $visibility = null,
    ) {}

    /** @param class-string<BulkDeleteHandler> $handler */
    public function register(string $resource, string $handler, ?string $owner = null): void
    {
        if (trim($resource) === '') {
            throw new InvalidArgumentException('Bulk delete resource key must not be empty.');
        }

        if (! is_subclass_of($handler, BulkDeleteHandler::class)) {
            throw new InvalidArgumentException("{$handler} must implement ".BulkDeleteHandler::class.'.');
        }

        if (isset($this->handlers[$resource])) {
            if ($this->handlers[$resource]['handler'] === $handler && $this->handlers[$resource]['owner'] === $owner) {
                return;
            }

            throw new LogicException("Bulk delete resource [{$resource}] is already registered to [{$this->handlers[$resource]['handler']}].");
        }

        $this->handlers[$resource] = ['handler' => $handler, 'owner' => $owner];
    }

    public function has(string $resource): bool
    {
        $entry = $this->handlers[$resource] ?? null;

        return $entry !== null && $this->isVisible($entry['owner']);
    }

    public function resolve(string $resource): ?BulkDeleteHandler
    {
        $entry = $this->handlers[$resource] ?? null;

        return $entry === null || ! $this->isVisible($entry['owner'])
            ? null
            : $this->container->make($entry['handler']);
    }

    /** @return array<string, class-string<BulkDeleteHandler>> */
    public function all(): array
    {
        $handlers = [];

        foreach ($this->handlers as $resource => $entry) {
            if ($this->isVisible($entry['owner'])) {
                $handlers[$resource] = $entry['handler'];
            }
        }

        return $handlers;
    }

    private function isVisible(?string $owner): bool
    {
        return $owner === null || ($this->visibility?->allows($owner) ?? true);
    }
}
