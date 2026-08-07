<?php

namespace App\Admin\Tables;

use App\Forms\Fields\InputField;
use App\Models\Category;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class CategoryTable extends BaseTable
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
