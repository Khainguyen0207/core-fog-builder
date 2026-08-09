<?php

namespace Modules\Cms\Admin\Tables;

use App\Models\BlogCategory;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class BlogCategoryTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(BlogCategory::class)
            ->setName('blog_categories')
            ->setNameTable('Blog Categories')
            ->setRoute('admin.blog-categories.index')
            ->hasFilter()
            ->usingQuery(
                BlogCategory::query()->withCount('posts')
            )
            ->addColumns([
                IDColumn::make('category_id'),
                Column::make('name')->setLabel('Name'),
                Column::make('slug')->setLabel('Slug'),
                Column::make('posts_count')->setLabel('Posts Count'),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.blog-categories.edit')
                    ->setAttribute('key', 'category_id')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.blog-categories.destroy')
                    ->setAttribute('key', 'category_id')
                    ->setDescription('Delete category'),
            ])
            ->addFilters([
                InputField::make('name')
                    ->setPlaceholder('Enter name...')
                    ->setName('name')
                    ->setLabel('Name'),
            ]);
    }
}
