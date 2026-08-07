<?php

namespace App\Admin\Forms;

use App\Enums\CouponRedemptionStatusEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Customer;

class CouponRedemptionForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(CouponRedemption::class)
            ->setTitle('Coupon Redemption')
            ->add(
                'coupon_id',
                SelectField::class,
                SelectField::make('coupon_id')
                    ->setLabel('Coupon')
                    ->setOptions(Coupon::query()->pluck('code', 'id')->toArray())
                    ->isRequired()
            )
            ->add(
                'customer_id',
                SelectField::class,
                SelectField::make('customer_id')
                    ->setLabel('Customer')
                    ->setOptions(Customer::query()->pluck('name', 'id')->toArray())
                    ->isRequired()
            )
            ->add(
                'context_type',
                InputField::class,
                InputField::make('context_type')
                    ->setLabel('Context Type')
                    ->setPlaceholder('e.g. booking, invoice, membership_transaction, order...')
                    ->helperText('booking | invoice | membership_transaction | order ...')
                    ->isRequired()
            )
            ->add(
                'discount_amount',
                InputField::class,
                InputField::make('discount_amount')
                    ->setLabel('Discount Amount')
                    ->setType('number')
                    ->setPlaceholder('Enter discount amount...')
                    ->isRequired()
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(CouponRedemptionStatusEnum::labels())
                    ->setDefaultValue(CouponRedemptionStatusEnum::APPLIED)
                    ->isRequired()
            );
    }
}
