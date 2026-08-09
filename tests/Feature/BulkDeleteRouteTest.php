<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BulkDeleteRouteTest extends TestCase
{
    public function test_legacy_app_bulk_delete_controller_is_not_routable(): void
    {
        $actions = collect(Route::getRoutes())->map->getActionName();

        $legacyController = 'App\\Http\\Controllers\\Admin\\BulkDeleteController';

        $this->assertNotContains($legacyController.'@bulkDelete', $actions);
        $this->assertNotContains($legacyController, $actions);
    }
}
