<?php

namespace App\Admin\Tables;

use App\Forms\Fields\InputField;
use App\Models\BlogCategory;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class BlogCategoryTable extends BaseTable
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
