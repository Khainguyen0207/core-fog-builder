<?php

namespace Tests\Feature\Modules\Cms;

use Illuminate\Support\Facades\Route;
use Modules\Cms\Admin\Tables\BlogCategoryTable;
use Modules\Cms\Admin\Tables\CommentTable;
use Modules\Cms\Admin\Tables\PostTable;
use Modules\Cms\Admin\Tables\TagTable;
use Modules\Cms\Http\Admin\Controllers\BlogCategoryController;
use Modules\Cms\Http\Admin\Controllers\CommentController;
use Modules\Cms\Http\Admin\Controllers\PostController;
use Modules\Cms\Http\Admin\Controllers\TagController;
use Modules\Shared\Tables\Registry\TableRegistry;
use Tests\TestCase;

class CmsAdminModuleTest extends TestCase
{
    public function test_cms_resources_are_registered_to_module_controllers(): void
    {
        $resources = [
            'posts' => [PostController::class, 'post'],
            'blog-categories' => [BlogCategoryController::class, 'blog_category'],
            'tags' => [TagController::class, 'tag'],
            'comments' => [CommentController::class, 'comment'],
        ];
        $actions = [
            'index' => ['GET', ''],
            'create' => ['GET', '/create'],
            'store' => ['POST', ''],
            'show' => ['GET', '/{%s}'],
            'edit' => ['GET', '/{%s}/edit'],
            'update' => ['PUT', '/{%s}'],
            'destroy' => ['DELETE', '/{%s}'],
        ];

        foreach ($resources as $resource => [$controller, $parameter]) {
            foreach ($actions as $action => [$method, $suffix]) {
                $route = Route::getRoutes()->getByName("admin.{$resource}.{$action}");

                $this->assertNotNull($route);
                $this->assertSame($controller.'@'.$action, $route->getActionName());
                $this->assertSame('admin/'.$resource.sprintf($suffix, $parameter), $route->uri());
                $this->assertContains($method, $route->methods());
                $this->assertSame(['web', 'plugin:figure-admin/cms', 'auth', 'ip.manager'], $route->gatherMiddleware());
            }
        }
    }

    public function test_cms_tables_and_views_are_registered(): void
    {
        $tables = app(TableRegistry::class);

        $this->assertSame(PostTable::class, $tables->resolve('posts'));
        $this->assertSame(BlogCategoryTable::class, $tables->resolve('blog_categories'));
        $this->assertSame(TagTable::class, $tables->resolve('tags'));
        $this->assertSame(CommentTable::class, $tables->resolve('comments'));
        $this->assertTrue(view()->exists('cms::forms.base'));
        $this->assertTrue(view()->exists('shared::tables.page'));
    }
}
