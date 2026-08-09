<?php

namespace Modules\Cms\Admin\Tables;

use App\Models\Tag;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class TagTable extends Table
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
