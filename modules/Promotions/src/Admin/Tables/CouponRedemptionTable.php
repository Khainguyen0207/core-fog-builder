<?php

namespace Modules\Promotions\Admin\Tables;

use App\Enums\CouponRedemptionStatusEnum;
use App\Models\CouponRedemption;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Table;

class CouponRedemptionTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(CouponRedemption::class)
            ->setName('coupon-redemptions')
            ->setNameTable('Coupon Redemptions')
            ->setRoute('admin.coupon-redemptions.index')
            ->hasFilter()
            ->notHeaderAction()
            ->operationsColumn(false)
            ->addColumns([
                IDColumn::make(),
                FormatColumn::make('coupon_id')
                    ->setLabel('Coupon')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->coupon ? $item->coupon->code : '-';
                    }),
                FormatColumn::make('customer_id')
                    ->setLabel('Customer')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->customer ? $item->customer->name : '-';
                    }),
                Column::make('context_type')->setLabel('Context Type'),
                Column::make('discount_amount')->setLabel('Discount Amount'),
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
            ->addFilters([
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(CouponRedemptionStatusEnum::labels()),
            ]);
    }
}
