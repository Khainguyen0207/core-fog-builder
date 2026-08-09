<?php

namespace Modules\Promotions\Admin\Tables;

use App\Enums\BasicStatusEnum;
use App\Enums\CouponTypeEnum;
use App\Models\Coupon;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class CouponTable extends Table
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
