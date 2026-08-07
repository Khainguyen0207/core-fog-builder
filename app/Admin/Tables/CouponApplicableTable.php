<?php

namespace App\Admin\Tables;

use App\Enums\CouponApplicableTypeEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Coupon;
use App\Models\CouponApplicable;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class CouponApplicableTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(CouponApplicable::class)
            ->setName('coupon-applicables')
            ->setNameTable('Coupon Applicables')
            ->setRoute('admin.coupon-applicables.index')
            ->hasFilter()
            ->addColumns([
                IDColumn::make(),
                FormatColumn::make('coupon_id')
                    ->setLabel('Coupon')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->coupon ? $item->coupon->code : '-';
                    }),
                FormatColumn::make('applicable_type')
                    ->setLabel('Applicable Type')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();
                        return $item->applicable_type->toHtml();
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
                    ->setActionUrl('admin.coupon-applicables.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.coupon-applicables.destroy')
                    ->setDescription('Do you want to delete coupon applicable ID '),
            ])
            ->addFilters([
                SelectField::make('coupon_id')
                    ->setName('coupon_id')
                    ->setLabel('Coupon')
                    ->hasFilter()
                    ->setOptions(Coupon::query()->pluck('code', 'id')->toArray()),
                SelectField::make('applicable_type')
                    ->setName('applicable_type')
                    ->setLabel('Applicable Type')
                    ->hasFilter()
                    ->setOptions(CouponApplicableTypeEnum::labels()),
            ]);
    }
}
