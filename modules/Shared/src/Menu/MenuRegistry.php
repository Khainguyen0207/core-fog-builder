<?php

namespace Modules\Shared\Menu;

use LogicException;
use Modules\Shared\Registry\Contracts\RegistrationVisibility;

class MenuRegistry
{
    private array $registered = [];

    private int $sequence = 0;

    public function __construct(private readonly ?RegistrationVisibility $visibility = null) {}

    public function register(string $key, array $item, int $order = 1000, ?string $owner = null): static
    {
        if (trim($key) === '') {
            throw new LogicException('Menu item key must not be empty.');
        }

        $entry = ['key' => $key, 'order' => $order, 'item' => $item, 'owner' => $owner];

        if (isset($this->registered[$key])) {
            $existing = $this->registered[$key];
            if ($existing['order'] === $order && $existing['item'] === $item && $existing['owner'] === $owner) {
                return $this;
            }

            throw new LogicException("Menu item [{$key}] is already registered.");
        }

        $entry['sequence'] = $this->sequence++;
        $this->registered[$key] = $entry;

        return $this;
    }

    public function all(): array
    {
        $entries = array_values(array_filter(
            $this->registered,
            fn (array $entry): bool => $entry['owner'] === null
                || ($this->visibility?->allows($entry['owner']) ?? true),
        ));
        usort($entries, fn (array $left, array $right): int => [$left['order'], $left['sequence']] <=> [$right['order'], $right['sequence']]);

        $items = [];
        foreach ($entries as $entry) {
            $items[$entry['key']] = $entry['item'];
        }

        return $items;
    }

    public function items(): array
    {
        return array_values($this->all());
    }
}
