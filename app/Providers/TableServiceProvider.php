<?php

namespace App\Providers;

use App\Table\BaseTable;
use App\Table\Configs\TableConfig;
use App\Table\Factory\TableFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class TableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TableConfig::class, fn() => new TableConfig);
        $this->app->singleton(TableFactory::class, fn($app) => new TableFactory($app->make(TableConfig::class)));
    }

    public function boot(): void
    {
        $dir = app_path('Admin/Tables');
        if (! is_dir($dir)) {
            return;
        }
        $cachePath = base_path('bootstrap/cache/tables.php');
        $metaPath = base_path('bootstrap/cache/tables.meta.php');

        if ($this->isCacheFresh($dir, $metaPath) && file_exists($cachePath)) {
            $map = require $cachePath;
            $this->registerMap($map);

            return;
        }

        $map = $this->discoverTables($dir);

        $this->writePhpArrayFile($cachePath, $map);
        $this->writePhpArrayFile($metaPath, [
            'fingerprint' => $this->fingerprint($dir),
            'generated_at' => date('c'),
        ]);

        $this->registerMap($map);
    }

    private function discoverTables(string $dir): array
    {
        $finder = new Finder;
        $finder->files()->in($dir)->name('*.php');

        $map = [];

        foreach ($finder as $file) {
            $class = 'App\\Admin\\Tables\\' . $file->getBasename('.php');

            if (! class_exists($class)) {
                continue;
            }

            if (is_subclass_of($class, BaseTable::class)) {
                try {
                    $key = Str::lower($class::make()->setup()->getName());
                    $map[$key] = $class;
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        ksort($map);

        return $map;
    }

    private function registerMap(array $map): void
    {
        $config = $this->app->make(TableConfig::class);

        foreach ($map as $key => $class) {
            $config->register($key, $class);
        }
    }

    private function isCacheFresh(string $dir, string $metaPath): bool
    {
        if (! file_exists($metaPath)) {
            return false;
        }

        $meta = require $metaPath;
        if (! is_array($meta) || ! isset($meta['fingerprint'])) {
            return false;
        }

        return hash_equals((string) $meta['fingerprint'], (string) $this->fingerprint($dir));
    }

    private function fingerprint(string $dir): string
    {
        $finder = new Finder;
        $finder->files()->in($dir)->name('*Table.php');

        $parts = [];

        foreach ($finder as $file) {

            $rel = str_replace($dir . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $parts[] = $rel . '|' . $file->getMTime();
        }

        sort($parts);

        return hash('sha256', implode("\n", $parts));
    }

    private function writePhpArrayFile(string $path, array $data): void
    {
        $content = "<?php\n\nreturn " . var_export($data, true) . ";\n";
        @file_put_contents($path, $content, LOCK_EX);
    }
}
