<?php

namespace Modules\Promotions\Admin\Forms;

use App\Enums\CouponApplicableTypeEnum;
use App\Models\Coupon;
use App\Models\CouponApplicable;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Forms\Form;

class CouponApplicableForm extends Form
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
