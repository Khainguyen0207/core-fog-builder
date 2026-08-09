<?php

namespace Modules\Communications\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Communications\Admin\Tables\EmailTemplateTable;
use Modules\Communications\Admin\Tables\SendEmailUserTable;

class CommunicationsServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'communications');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('email_templates', EmailTemplateTable::class);
        $tables->register('send_email_users', SendEmailUserTable::class);
    }
}
