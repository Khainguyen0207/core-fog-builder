<?php

namespace Modules\Catalog\Admin\Tables;

use App\Models\Category;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class CategoryTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Category::class)
            ->setName('categories')
            ->setNameTable('Categories')
            ->setRoute('admin.categories.index')
            ->hasFilter()
            ->usingQuery(
                Category::query()
                    ->select(['id', 'name', 'updated_at'])
            )
            ->addColumns([
                IDColumn::make(),
                Column::make('name')->setLabel('Name'),
                FormatColumn::make('updated_at')
                    ->setLabel('Updated At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->updated_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.categories.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.categories.destroy')
                    ->setDescription('Do you want to delete category ID '),
            ])
            ->addFilters([
                InputField::make('name')
                    ->setName('name')
                    ->setPlaceholder('Enter Name...')
                    ->setLabel('Name'),
            ]);
    }
}
