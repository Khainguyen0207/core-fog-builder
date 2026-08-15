<?php

namespace Modules\Communications\Providers;

use App\Actions\SendBookingNotificationAction;
use App\Plugins\PluginManager;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\Communications\Admin\Tables\EmailTemplateTable;
use Modules\Communications\Admin\Tables\SendEmailUserTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;
use Telegram\Bot\Laravel\TelegramServiceProvider;

class CommunicationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(TelegramServiceProvider::class);
    }

    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'communications');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        Schedule::call(fn () => app(SendBookingNotificationAction::class)->handle())
            ->dailyAt('9:30')
            ->when(fn (): bool => app(PluginManager::class)->isEnabled('figure-admin/communications'));

        $tables->register('email_templates', EmailTemplateTable::class, 'figure-admin/communications');
        $tables->register('send_email_users', SendEmailUserTable::class, 'figure-admin/communications');
        $menus->register('communications', [
            'name' => 'Email Marketing', 'icon' => 'menu-icon tf-icons bx bx-envelope',
            'route' => 'admin.email-templates.index', 'active' => ['admin.email-templates.*', 'admin.send-email.*'],
            'children' => [
                ['name' => 'Templates', 'route' => 'admin.email-templates.index', 'active' => ['admin.email-templates.*']],
                ['name' => 'Send Email', 'route' => 'admin.send-email.index', 'active' => ['admin.send-email.*']],
            ],
        ], 1000, 'figure-admin/communications');
    }
}
