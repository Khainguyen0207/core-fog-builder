<?php

namespace Tests\Feature\Modules\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;
use InvalidArgumentException;
use LogicException;
use Modules\Shared\BulkActions\BulkDeleteRegistry;
use Modules\Shared\BulkActions\Contracts\BulkDeleteHandler;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Http\Controllers\BulkDeleteController;
use Modules\Shared\Http\Controllers\DataTableController;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Panels\Panel;
use Modules\Shared\Panels\PanelSection;
use Modules\Shared\Registry\Contracts\RegistrationVisibility;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Factory\TableFactory;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Registry\TableRegistry;
use Modules\Shared\Tables\Table;
use Modules\Shared\View\NavbarUserPresenter;
use Tests\TestCase;

class SharedCoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('shared_core_records', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->integer('status');
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('shared_core_records');

        parent::tearDown();
    }

    public function test_provider_registers_config_views_bindings_and_route(): void
    {
        $defaults = require dirname(__DIR__, 4).'/modules/Shared/config/figure-admin-shared.php';

        $this->assertSame('admin', $defaults['routes']['prefix']);
        $this->assertSame('admin.', $defaults['routes']['name_prefix']);
        $this->assertSame(['web', 'auth'], $defaults['routes']['middleware']);
        $this->assertSame([
            'modules/Shared/resources/js/app.js',
            'modules/Shared/resources/scss/admin.scss',
        ], $defaults['assets']['vite']);
        $this->assertTrue(config('figure-admin-shared.routes.enabled'));
        $this->assertSame('admin', config('figure-admin-shared.routes.prefix'));
        $this->assertArrayHasKey('shared', app('view')->getFinder()->getHints());
        $this->assertSame(app(TableRegistry::class), app(TableRegistry::class));
        $this->assertSame(app(BulkDeleteRegistry::class), app(BulkDeleteRegistry::class));
        $this->assertSame(app(MenuRegistry::class), app(MenuRegistry::class));
        $this->assertInstanceOf(TableFactory::class, app(TableFactory::class));
        $this->assertInstanceOf(NavbarUserPresenter::class, app(NavbarUserPresenter::class));
        $this->assertTrue(Route::has('admin.get-data'));

        $route = Route::getRoutes()->getByName('admin.get-data');

        $this->assertSame('admin/get-data/{table}', $route?->uri());
        $this->assertSame(DataTableController::class, $route?->getActionName());
        $this->assertContains('web', $route?->gatherMiddleware() ?? []);
        $this->assertContains('auth', $route?->gatherMiddleware() ?? []);

        $bulkDeleteRoute = Route::getRoutes()->getByName('admin.bulk-delete');

        $this->assertSame('admin/bulk-delete', $bulkDeleteRoute?->uri());
        $this->assertSame(['POST'], $bulkDeleteRoute?->methods());
        $this->assertSame(BulkDeleteController::class, $bulkDeleteRoute?->getActionName());
        $this->assertContains('web', $bulkDeleteRoute?->gatherMiddleware() ?? []);
        $this->assertContains('auth', $bulkDeleteRoute?->gatherMiddleware() ?? []);
    }

    public function test_panel_state_is_initialized_and_panel_section_renders_package_view(): void
    {
        $panel = Panel::make('system')
            ->setDescription('System settings')
            ->setUrl('/settings');

        $this->assertSame('system', $panel->getName());
        $this->assertSame('System settings', $panel->getDescription());
        $this->assertSame('/settings', $panel->getUrl());
        $this->assertSame('Setup', $panel->getButtonLabel());
        $this->assertSame('shared::panels.card', $panel->getTemplate());

        $section = SharedCorePanelSection::make();
        $view = $section->renderPanel();

        $this->assertSame('shared::panels.page', $view->name());
        $this->assertSame('Shared panels', $view->getData()['title']);
        $this->assertCount(1, $section->getPanels());
    }

    public function test_menu_registry_orders_items_and_detects_key_collisions(): void
    {
        $registry = new MenuRegistry;
        $customers = ['name' => 'Customers', 'children' => [['name' => 'Membership', 'route' => 'admin.membership.index']], 'active' => ['admin.customers.*']];

        $registry->register('customers', $customers, 200);
        $registry->register('dashboard', ['name' => 'Dashboard'], 100);
        $registry->register('customers', $customers, 200);

        $this->assertSame(['dashboard', 'customers'], array_keys($registry->all()));
        $this->assertSame(['Dashboard', 'Customers'], array_column($registry->items(), 'name'));

        $this->expectException(LogicException::class);
        $registry->register('customers', ['name' => 'Other'], 200);
    }

    public function test_feature_modules_contribute_their_menu_items_in_stable_order(): void
    {
        $keys = array_keys(app(MenuRegistry::class)->all());
        $expected = [
            'dashboard', 'customers', 'users', 'settings', 'auth.logout',
        ];

        $this->assertSame($expected, array_values(array_intersect($keys, $expected)));
    }

    public function test_registries_hide_owned_entries_without_losing_collision_protection(): void
    {
        $visibility = new class implements RegistrationVisibility
        {
            public bool $enabled = false;

            public function allows(string $owner): bool
            {
                return $this->enabled && $owner === 'figure-admin/example';
            }
        };
        $menus = new MenuRegistry($visibility);
        $tables = new TableRegistry($visibility);
        $bulkDeletes = new BulkDeleteRegistry($this->app, $visibility);

        $menus->register('example', ['name' => 'Example'], 100, 'figure-admin/example');
        $tables->register('example', SharedCoreTable::class, 'figure-admin/example');
        $bulkDeletes->register('example', SharedCoreBulkDeleteHandler::class, 'figure-admin/example');

        $this->assertSame([], $menus->all());
        $this->assertSame([], $tables->all());
        $this->assertFalse($bulkDeletes->has('example'));
        $this->assertNull($bulkDeletes->resolve('example'));

        try {
            $tables->resolve('example');
            $this->fail('A hidden table was resolved.');
        } catch (InvalidArgumentException) {
            $this->assertTrue(true);
        }

        $visibility->enabled = true;

        $this->assertSame(['example'], array_keys($menus->all()));
        $this->assertSame(SharedCoreTable::class, $tables->resolve('example'));
        $this->assertInstanceOf(SharedCoreBulkDeleteHandler::class, $bulkDeletes->resolve('example'));

        $this->expectException(LogicException::class);
        $tables->register('example', SharedCoreTable::class, 'figure-admin/other');
    }

    public function test_package_views_and_nullable_user_presenter_do_not_require_host_models(): void
    {
        $presenter = app(NavbarUserPresenter::class);

        $this->assertSame([
            'email' => '',
            'avatar' => 'default-avatar.png',
            'role' => '',
        ], $presenter->present(null));
        $this->assertSame('Administrator', $presenter->present((object) [
            'email' => 'admin@example.test',
            'role' => new class
            {
                public function getLabel(): string
                {
                    return 'Administrator';
                }
            },
        ])['role']);
        $this->assertSame('member', $presenter->present((object) ['role' => SharedCoreRole::MEMBER])['role']);

        $html = view('shared::panels.card', ['panel' => Panel::make('Package panel')])->render();

        $this->assertStringContainsString('Package panel', $html);
        $this->assertStringNotContainsString('admin.components', $html);
    }

    public function test_registry_allows_idempotent_registration_and_rejects_duplicate_keys(): void
    {
        $registry = new TableRegistry;

        $registry->register('records', SharedCoreTable::class);
        $registry->register('records', SharedCoreTable::class);

        $this->assertSame(SharedCoreTable::class, $registry->resolve('records'));
        $this->assertSame(['records' => SharedCoreTable::class], $registry->all());

        $this->expectException(LogicException::class);
        $registry->register('records', OtherSharedCoreTable::class);
    }

    public function test_bulk_delete_registry_validates_handlers_and_rejects_conflicting_keys(): void
    {
        $registry = app(BulkDeleteRegistry::class);

        $registry->register('records', SharedCoreBulkDeleteHandler::class);
        $registry->register('records', SharedCoreBulkDeleteHandler::class);

        $this->assertTrue($registry->has('records'));
        $this->assertInstanceOf(SharedCoreBulkDeleteHandler::class, $registry->resolve('records'));
        $this->assertSame(['records' => SharedCoreBulkDeleteHandler::class], $registry->all());

        try {
            $registry->register('invalid', SharedCoreTable::class);
            $this->fail('An invalid bulk delete handler was registered.');
        } catch (InvalidArgumentException) {
            $this->assertFalse($registry->has('invalid'));
        }

        $this->expectException(LogicException::class);
        $registry->register('records', UnauthorizedSharedCoreBulkDeleteHandler::class);
    }

    public function test_bulk_delete_endpoint_rejects_unknown_resources_with_consistent_envelope(): void
    {
        $response = $this->withoutMiddleware()->postJson('/admin/bulk-delete', [
            'resource' => 'unknown',
            'ids' => [1],
        ]);

        $response->assertNotFound()->assertExactJson([
            'error' => true,
            'data' => null,
            'message' => 'The requested resource does not support bulk deletion.',
        ]);
    }

    public function test_bulk_delete_endpoint_requires_a_nonempty_integer_id_array(): void
    {
        $response = $this->withoutMiddleware()->postJson('/admin/bulk-delete', [
            'resource' => 'records',
            'ids' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonPath('error', true)
            ->assertJsonPath('message', 'The bulk delete request is invalid.')
            ->assertJsonStructure(['data' => ['errors' => ['ids']]]);
    }

    public function test_bulk_delete_endpoint_returns_forbidden_for_unauthorized_handler(): void
    {
        app(BulkDeleteRegistry::class)->register('records', UnauthorizedSharedCoreBulkDeleteHandler::class);

        $response = $this->withoutMiddleware()->postJson('/admin/bulk-delete', [
            'resource' => 'records',
            'ids' => [1],
        ]);

        $response->assertForbidden()->assertExactJson([
            'error' => true,
            'data' => null,
            'message' => 'You are not authorized to bulk delete this resource.',
        ]);
    }

    public function test_bulk_delete_endpoint_resolves_handler_and_deletes_inside_transaction(): void
    {
        $first = SharedCoreRecord::query()->create(['name' => 'First', 'status' => 1]);
        $second = SharedCoreRecord::query()->create(['name' => 'Second', 'status' => 1]);
        SharedCoreRecord::query()->create(['name' => 'Kept', 'status' => 1]);
        SharedCoreBulkDeleteHandler::$ranInsideTransaction = false;
        app(BulkDeleteRegistry::class)->register('records', SharedCoreBulkDeleteHandler::class);

        $response = $this->withoutMiddleware()->post('/admin/bulk-delete', [
            'resource' => 'records',
            'ids' => [(string) $first->getKey(), (string) $second->getKey()],
        ]);

        $response->assertOk()->assertExactJson([
            'error' => false,
            'data' => [
                'deleted_count' => 2,
                'ids' => [$first->getKey(), $second->getKey()],
            ],
            'message' => 'Selected records were deleted successfully.',
        ]);
        $this->assertTrue(SharedCoreBulkDeleteHandler::$ranInsideTransaction);
        $this->assertDatabaseMissing('shared_core_records', ['id' => $first->getKey()]);
        $this->assertDatabaseMissing('shared_core_records', ['id' => $second->getKey()]);
        $this->assertDatabaseHas('shared_core_records', ['name' => 'Kept']);
    }

    public function test_shared_tables_do_not_expose_bulk_delete_by_default(): void
    {
        $table = (new SharedCoreTable)->setup();
        view()->share('errors', new ViewErrorBag);

        $this->assertFalse($table->isHasBulkDelete());
        $this->assertStringNotContainsString(
            '<div class="modal fade" id="bulk-confirm-modal-records"',
            $table->renderTable()->render(),
        );
    }

    public function test_configured_query_is_cloned_without_losing_constraints(): void
    {
        $table = (new SharedCoreTable)->setup();
        $configuredQuery = $table->configuredQuery();
        $resolvedQuery = $table->getUsingQuery();

        $this->assertNotSame($configuredQuery, $resolvedQuery);
        $this->assertSame($configuredQuery->toSql(), $resolvedQuery->toSql());
        $this->assertSame($configuredQuery->getBindings(), $resolvedQuery->getBindings());
        $this->assertStringContainsString('"status" = ?', $resolvedQuery->toSql());
    }

    public function test_malformed_search_json_does_not_crash_data_table_endpoint(): void
    {
        SharedCoreRecord::query()->create(['name' => 'Visible', 'status' => 1]);
        SharedCoreRecord::query()->create(['name' => 'Hidden', 'status' => 0]);

        $registry = app(TableRegistry::class);
        $registry->register('records', SharedCoreTable::class);

        $request = Request::create('/admin/get-data/records', 'POST', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => '{malformed-json', 'regex' => false],
        ]);
        $this->app->instance('request', $request);

        $response = app(DataTableController::class)('records', app(TableFactory::class));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(1, $payload['recordsFiltered']);
        $this->assertSame('Visible', $payload['data'][0]['name']);
    }

    public function test_operations_are_rendered_server_side_with_final_keyed_urls(): void
    {
        Route::get('/shared-test/records/{record}/edit', fn () => null)->name('shared-test.records.edit');
        Route::delete('/shared-test/records/{record}', fn () => null)->name('shared-test.records.destroy');
        Route::getRoutes()->refreshNameLookups();
        SharedCoreRecord::query()->create(['name' => 'record-key', 'status' => 1]);

        $request = Request::create('/shared-test/data', 'POST', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]);
        $this->app->instance('request', $request);

        $table = (new SharedCoreOperationsTable)->setup();
        $columns = json_decode($table->getColumnsToJson(), true, 512, JSON_THROW_ON_ERROR);
        $payload = $table->getDataTable()->getData(true);
        $operations = $payload['data'][0]['operations'];

        $this->assertSame('operations', $columns[array_key_last($columns)]['data']);
        $this->assertStringContainsString('/shared-test/records/record-key/edit', $operations);
        $this->assertStringContainsString('/shared-test/records/record-key', $operations);
        $this->assertStringNotContainsString('/0', $operations);
    }

    public function test_shared_views_emit_runtime_hooks_without_inline_scripts(): void
    {
        $table = (new SharedCoreTable)->setup();
        view()->share('errors', new ViewErrorBag);
        $html = $table->renderTable()->render();

        $this->assertStringContainsString('data-shared-table', $html);
        $this->assertStringContainsString('data-shared-table-config', $html);
        $this->assertStringContainsString('data-bs-target="#filter-collapse-records"', $html);
        $this->assertStringContainsString('id="filter-collapse-records" class="collapse"', $html);
        $this->assertStringContainsString('bx-filter-alt', $html);
        $this->assertStringContainsString('bx-reset', $html);

        $viewPath = dirname(__DIR__, 4).'/modules/Shared/resources/views';
        $this->assertStringContainsString('data-shared-form', File::get($viewPath.'/forms/form.blade.php'));
        $this->assertStringContainsString('data-shared-editor', File::get($viewPath.'/forms/fields/editor.blade.php'));
        $this->assertStringContainsString('data-shared-file-preview', File::get($viewPath.'/forms/fields/input.blade.php'));
        $this->assertStringContainsString('data-shared-menu-search', File::get($viewPath.'/layouts/sections/navbar/navbar.blade.php'));

        foreach (File::allFiles($viewPath) as $view) {
            $contents = $view->getContents();
            $withoutJson = preg_replace('/<script\s+type="application\/json"[^>]*>.*?<\/script>/s', '', $contents);
            $withoutExternalScripts = preg_replace('/<script\s+[^>]*src="[^"]+"[^>]*>\s*<\/script>/s', '', $withoutJson);

            $this->assertStringNotContainsString('<script', $withoutExternalScripts, $view->getPathname());
        }
    }
}

class SharedCoreRecord extends Model
{
    public $timestamps = false;

    protected $table = 'shared_core_records';

    protected $fillable = ['name', 'status'];
}

class SharedCoreTable extends Table
{
    private mixed $configuredQuery = null;

    public function setup(): static
    {
        parent::setup();

        $this->configuredQuery = SharedCoreRecord::query()->where('status', 1);

        return $this->setModel(SharedCoreRecord::class)
            ->setName('records')
            ->setNameTable('Records')
            ->hasCheckbox(false)
            ->operationsColumn(false)
            ->hasFilter()
            ->usingQuery($this->configuredQuery)
            ->addColumns([
                Column::make('id'),
                Column::make('name'),
                Column::make('status'),
            ])
            ->addFilters([
                InputField::make('name')->setLabel('Name')->hasFilter(),
            ]);
    }

    public function configuredQuery(): mixed
    {
        return $this->configuredQuery;
    }
}

class OtherSharedCoreTable extends Table {}

class SharedCoreOperationsTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this->setModel(SharedCoreRecord::class)
            ->setName('operation-records')
            ->hasCheckbox(false)
            ->usingQuery(SharedCoreRecord::query())
            ->addColumns([
                Column::make('id'),
                Column::make('name'),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('shared-test.records.edit')
                    ->setAttribute('key', 'name')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('shared-test.records.destroy')
                    ->setAttribute('key', 'name'),
            ]);
    }
}

class SharedCoreBulkDeleteHandler implements BulkDeleteHandler
{
    public static bool $ranInsideTransaction = false;

    public function authorize(Request $request, array $ids): bool
    {
        return true;
    }

    public function delete(array $ids): int
    {
        self::$ranInsideTransaction = DB::connection()->transactionLevel() > 0;

        return SharedCoreRecord::query()->whereKey($ids)->delete();
    }
}

class UnauthorizedSharedCoreBulkDeleteHandler implements BulkDeleteHandler
{
    public function authorize(Request $request, array $ids): bool
    {
        return false;
    }

    public function delete(array $ids): int
    {
        throw new LogicException('Unauthorized handlers must not delete records.');
    }
}

class SharedCorePanelSection extends PanelSection
{
    public function setup(): static
    {
        parent::setup();

        return $this->setNameTable('Shared panels')
            ->addPanels([Panel::make('General')]);
    }
}

enum SharedCoreRole: string
{
    case MEMBER = 'member';
}
