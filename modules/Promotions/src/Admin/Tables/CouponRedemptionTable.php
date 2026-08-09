<?php

namespace Modules\Promotions\Admin\Tables;

use App\Enums\CouponRedemptionStatusEnum;
use App\Forms\Fields\SelectField;
use App\Models\CouponRedemption;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use Modules\AdminUi\Tables\ModuleTable;

class CouponRedemptionTable extends ModuleTable
{
    protected string $moduleView = 'promotions::tables.index';

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
