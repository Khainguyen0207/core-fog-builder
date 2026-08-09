<?php

namespace Modules\Cms\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Cms\Admin\Tables\BlogCategoryTable;
use Modules\Cms\Admin\Tables\CommentTable;
use Modules\Cms\Admin\Tables\PostTable;
use Modules\Cms\Admin\Tables\TagTable;

class CmsServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cms');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('posts', PostTable::class);
        $tables->register('blog_categories', BlogCategoryTable::class);
        $tables->register('tags', TagTable::class);
        $tables->register('comments', CommentTable::class);
    }
}
