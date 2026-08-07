<?php

namespace App\Admin\Tables;

use App\Enums\BaseStatusEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Category;
use App\Models\Service;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class ServiceTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Service::class)
            ->setName('services')
            ->setNameTable('Services')
            ->setRoute('admin.services.index')
            ->hasFilter()
            ->usingQuery(
                Service::query()
                    ->select(['id', 'title', 'category_id', 'status', 'price', 'updated_at'])
                    ->with('category')
            )
            ->addColumns([
                IDColumn::make(),
                Column::make('title')->setLabel('Title'),
                FormatColumn::make('category_id')
                    ->setLabel('Category')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->category ? $item->category->name : '-';
                    }),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();
                        $status = $item->status;
                        $color = $status === 'enabled' ? 'success' : 'primary';
                        $label = $status === 'enabled' ? 'Enabled' : 'Disabled';

                        return '<span class="badge bg-label-'.$color.'">'.$label.'</span>';
                    }),
                Column::make('price')->setLabel('Price'),
                FormatColumn::make('updated_at')
                    ->setLabel('Updated At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->updated_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.services.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.services.destroy')
                    ->setDescription('Do you want to delete service ID '),
            ])
            ->addFilters([
                InputField::make('title')
                    ->setName('title')
                    ->setPlaceholder('Enter Title...')
                    ->setLabel('Title'),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(BaseStatusEnum::labels()),
                SelectField::make('category_id')
                    ->setName('category_id')
                    ->setLabel('Category')
                    ->hasFilter()
                    ->setOptions(
                        Category::query()->pluck('name', 'id')->toArray()
                    ),
            ]);
    }
}
