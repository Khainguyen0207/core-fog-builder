<?php

namespace Modules\AdminUi\Providers;

use Illuminate\Support\ServiceProvider;

class AdminUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');
    }
}
