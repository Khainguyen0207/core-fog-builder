<?php

namespace Modules\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Cms\Admin\Tables\BlogCategoryTable;
use Modules\Cms\Admin\Tables\CommentTable;
use Modules\Cms\Admin\Tables\PostTable;
use Modules\Cms\Admin\Tables\TagTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class CmsServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cms');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('posts', PostTable::class);
        $tables->register('blog_categories', BlogCategoryTable::class);
        $tables->register('tags', TagTable::class);
        $tables->register('comments', CommentTable::class);
        $menus->register('cms', [
            'name' => 'Blog', 'icon' => 'menu-icon tf-icons bx bx-receipt',
            'route' => 'admin.posts.index', 'active' => ['admin.posts.*', 'admin.tags.*', 'admin.blog-categories.*'],
            'children' => [
                ['name' => 'Posts', 'route' => 'admin.posts.index', 'active' => ['admin.posts.*']],
                ['name' => 'Tags', 'route' => 'admin.tags.index', 'active' => ['admin.tags.*']],
                ['name' => 'Categories', 'route' => 'admin.blog-categories.index', 'active' => ['admin.blog-categories.*']],
            ],
        ], 900);
    }
}
