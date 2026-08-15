<?php

namespace Tests\Feature\Modules\Communications;

use App\Plugins\PluginManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Modules\Communications\Admin\Tables\EmailTemplateTable;
use Modules\Communications\Admin\Tables\SendEmailUserTable;
use Modules\Communications\Http\Admin\Controllers\EmailTemplateController;
use Modules\Communications\Http\Admin\Controllers\SendEmailController;
use Modules\Communications\Http\Admin\Controllers\TelegramBotController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class CommunicationsAdminModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PluginManager::class)->replaceEnabledPackages([
            'figure-admin/catalog',
            'figure-admin/workforce',
            'figure-admin/booking',
            'figure-admin/communications',
        ]);
    }

    public function test_communications_admin_routes_are_registered_to_module_controllers(): void
    {
        $routes = [
            'admin.email-templates.preview' => [EmailTemplateController::class.'@preview', 'admin/email-templates/{emailTemplate}/preview', 'GET'],
            'admin.email-templates.index' => [EmailTemplateController::class.'@index', 'admin/email-templates', 'GET'],
            'admin.email-templates.show' => [EmailTemplateController::class.'@show', 'admin/email-templates/{email_template}', 'GET'],
            'admin.email-templates.edit' => [EmailTemplateController::class.'@edit', 'admin/email-templates/{email_template}/edit', 'GET'],
            'admin.email-templates.update' => [EmailTemplateController::class.'@update', 'admin/email-templates/{email_template}', 'PUT'],
            'admin.email-templates.destroy' => [EmailTemplateController::class.'@destroy', 'admin/email-templates/{email_template}', 'DELETE'],
            'admin.send-email.index' => [SendEmailController::class.'@index', 'admin/send-email', 'GET'],
            'admin.send-email.preview' => [SendEmailController::class.'@getTemplatePreview', 'admin/send-email/preview/{id}', 'GET'],
            'admin.send-email.send' => [SendEmailController::class.'@send', 'admin/send-email/send', 'POST'],
        ];

        foreach ($routes as $name => [$controller, $uri, $method]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame($controller, $route->getActionName());
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $this->assertSame(['web', 'plugin:figure-admin/communications', 'auth', 'ip.manager'], $route->gatherMiddleware());
        }

        $routeNames = array_map(
            fn ($route) => $route->getName(),
            Route::getRoutes()->getRoutes()
        );

        $this->assertLessThan(
            array_search('admin.email-templates.index', $routeNames, true),
            array_search('admin.email-templates.preview', $routeNames, true)
        );
    }

    public function test_communications_tables_and_views_are_registered(): void
    {
        $tables = app(TableRegistry::class);

        $this->assertSame(EmailTemplateTable::class, $tables->resolve('email_templates'));
        $this->assertSame(SendEmailUserTable::class, $tables->resolve('send_email_users'));
        $this->assertTrue(view()->exists('communications::forms.base'));
        $this->assertTrue(view()->exists('shared::tables.page'));
        $this->assertTrue(view()->exists('communications::admin.pages.email.send'));
        $this->assertTrue(view()->exists('communications::admin.pages.email.preview'));
        $this->assertTrue(view()->exists('communications::admin.templates.email-templates.booking-notification'));
        $this->assertTrue(view()->exists('communications::admin.templates.email-templates.otp-template'));
    }

    public function test_updated_activity_is_an_unnamed_guest_route_to_the_module_controller(): void
    {
        $route = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($route) => $route->uri() === 'updated-activity' && in_array('GET', $route->methods(), true));

        $this->assertNotNull($route);
        $this->assertNull($route->getName());
        $this->assertSame(TelegramBotController::class.'@updatedActivity', $route->getActionName());
        $this->assertSame(['web', 'plugin:figure-admin/communications', 'guest'], $route->gatherMiddleware());
    }
}
