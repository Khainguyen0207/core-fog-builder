<?php

namespace App\Admin\Tables;

use App\Enums\BasicStatusEnum;
use App\Enums\CouponTypeEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Coupon;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class CouponTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Coupon::class)
            ->setName('coupons')
            ->setNameTable('Coupons')
            ->setRoute('admin.coupons.index')
            ->hasFilter()
            ->addColumns([
                IDColumn::make(),
                Column::make('code')->setLabel('Code'),
                FormatColumn::make('type')
                    ->setLabel('Type')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();
                        return $item->type->toHtml();
                    }),
                Column::make('value')->setLabel('Value'),
                FormatColumn::make('starts_at')
                    ->setLabel('Starts At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->starts_at?->format('Y-m-d H:i') ?? '-';
                    }),
                FormatColumn::make('ends_at')
                    ->setLabel('Ends At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->ends_at?->format('Y-m-d H:i') ?? '-';
                    }),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();
                        return $item->status->toHtml();
                    }),
                FormatColumn::make('updated_at')
                    ->setLabel('Updated At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->updated_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.coupons.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.coupons.destroy')
                    ->setDescription('Do you want to delete coupon ID '),
            ])
            ->addFilters([
                InputField::make('code')
                    ->setName('code')
                    ->setPlaceholder('Enter Code...')
                    ->setLabel('Code'),
                SelectField::make('type')
                    ->setName('type')
                    ->setLabel('Type')
                    ->hasFilter()
                    ->setOptions(CouponTypeEnum::labels()),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(BasicStatusEnum::labels()),
            ]);
    }
}
