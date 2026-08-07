<?php

namespace App\Admin\Forms;

use App\Enums\BasicStatusEnum;
use App\Enums\CouponStatusEnum;
use App\Enums\CouponTypeEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Coupon;

class CouponForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Coupon::class)
            ->setTitle('Coupon')
            ->add(
                'code',
                InputField::class,
                InputField::make('code')
                    ->setLabel('Code')
                    ->setPlaceholder('Enter coupon code...')
                    ->isRequired()
            )
            ->add(
                'type',
                SelectField::class,
                SelectField::make('type')
                    ->setLabel('Type')
                    ->setOptions(CouponTypeEnum::labels())
                    ->setDefaultValue(CouponTypeEnum::PERCENTAGE)
                    ->isRequired()
            )
            ->add(
                'value',
                InputField::class,
                InputField::make('value')
                    ->setLabel('Value')
                    ->setType('number')
                    ->setPlaceholder('Enter value...')
                    ->helperText('Percentage (e.g. 10.00) or fixed amount (e.g. 100000.00)')
                    ->isRequired()
            )
            ->add(
                'starts_at',
                InputField::class,
                InputField::make('starts_at')
                    ->setLabel('Starts At')
                    ->setType('datetime-local')
            )
            ->add(
                'ends_at',
                InputField::class,
                InputField::make('ends_at')
                    ->setLabel('Ends At')
                    ->setType('datetime-local')
            )
            ->add(
                'max_redemptions',
                InputField::class,
                InputField::make('max_redemptions')
                    ->setLabel('Max Redemptions')
                    ->setType('number')
                    ->setPlaceholder('Leave empty for unlimited')
                    ->helperText('Total maximum redemptions allowed (empty = unlimited)')
            )
            ->add(
                'max_redemptions_per_user',
                InputField::class,
                InputField::make('max_redemptions_per_user')
                    ->setLabel('Max Redemptions Per User')
                    ->setType('number')
                    ->setPlaceholder('Leave empty for unlimited')
                    ->helperText('Maximum redemptions per user (empty = unlimited)')
            )
            ->add(
                'min_order_amount',
                InputField::class,
                InputField::make('min_order_amount')
                    ->setLabel('Min Order Amount')
                    ->setType('number')
                    ->setPlaceholder('Leave empty for no minimum')
                    ->helperText('Minimum order amount to apply coupon (empty = no minimum)')
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(BasicStatusEnum::labels())
                    ->setDefaultValue(BasicStatusEnum::PUBLISHED)
                    ->isRequired()
            );
    }
}
