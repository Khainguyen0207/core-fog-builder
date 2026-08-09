<?php

namespace Modules\Promotions\Admin\Forms;

use App\Enums\CouponApplicableTypeEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\SelectField;
use App\Models\Coupon;
use App\Models\CouponApplicable;

class CouponApplicableForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(CouponApplicable::class)
            ->setTemplate('promotions::forms.base')
            ->setTitle('Coupon Applicable')
            ->add(
                'coupon_id',
                SelectField::class,
                SelectField::make('coupon_id')
                    ->setLabel('Coupon')
                    ->setOptions(Coupon::query()->pluck('code', 'id')->toArray())
                    ->isRequired()
            )
            ->add(
                'applicable_type',
                SelectField::class,
                SelectField::make('applicable_type')
                    ->setLabel('Applicable Type')
                    ->setOptions(CouponApplicableTypeEnum::labels())
                    ->isRequired()
            );
    }
}
