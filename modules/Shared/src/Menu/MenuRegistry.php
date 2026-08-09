<?php

namespace Modules\Shared\Menu;

use LogicException;

class MenuRegistry
{
    private array $registered = [];

    private int $sequence = 0;

    public function register(string $key, array $item, int $order = 1000): static
    {
        if (trim($key) === '') {
            throw new LogicException('Menu item key must not be empty.');
        }

        $entry = ['key' => $key, 'order' => $order, 'item' => $item];

        if (isset($this->registered[$key])) {
            $existing = $this->registered[$key];
            if ($existing['order'] === $order && $existing['item'] === $item) {
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
        $entries = array_values($this->registered);
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
