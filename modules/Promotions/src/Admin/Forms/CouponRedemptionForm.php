<?php

namespace Modules\Promotions\Admin\Forms;

use App\Enums\CouponRedemptionStatusEnum;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Customer;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Forms\Form;

class CouponRedemptionForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(CouponRedemption::class)
            ->setTemplate('promotions::forms.base')
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
