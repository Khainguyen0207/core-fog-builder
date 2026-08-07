<?php

namespace App\Admin\Tables;

use App\Forms\Fields\InputField;
use App\Models\Tag;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class TagTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Tag::class)
            ->setName('tags')
            ->setNameTable('Tags')
            ->setRoute('admin.tags.index')
            ->hasFilter()
            ->usingQuery(Tag::query())
            ->addColumns([
                IDColumn::make('tag_id'),
                Column::make('name')->setLabel('Name'),
                Column::make('slug')->setLabel('Slug'),
            ])
            ->addOperations([
                EditOperation::make()->setActionUrl('admin.tags.edit')
                    ->setAttribute('key', 'tag_id')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setAttribute('key', 'tag_id')
                    ->setDataActionUrl('admin.tags.destroy')->setDescription('Delete tag?'),
            ])
            ->addFilters([
                InputField::make('name')->setName('name')
                    ->setPlaceholder('Enter name...')
                    ->setLabel('Name'),
            ]);
    }
}
