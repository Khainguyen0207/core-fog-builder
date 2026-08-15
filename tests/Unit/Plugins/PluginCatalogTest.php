<?php

namespace Tests\Unit\Plugins;

use App\Plugins\PluginCatalog;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PluginCatalogTest extends TestCase
{
    public function test_it_rejects_unknown_dependencies(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('unknown dependency');

        new PluginCatalog([
            'vendor/plugin' => $this->definition(['vendor/missing']),
        ]);
    }

    public function test_it_rejects_dependency_cycles(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('dependency cycle');

        new PluginCatalog([
            'vendor/one' => $this->definition(['vendor/two']),
            'vendor/two' => $this->definition(['vendor/one']),
        ]);
    }

    /** @param list<string> $dependencies */
    private function definition(array $dependencies): array
    {
        return [
            'display_name' => 'Plugin',
            'core' => false,
            'dependencies' => $dependencies,
        ];
    }
}
