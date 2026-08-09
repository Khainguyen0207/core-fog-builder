<?php

namespace Modules\Customers\Admin\Forms;

use App\Enums\BasicStatusEnum;
use App\Models\MembershipSetting;
use Illuminate\Database\Eloquent\Model;
use Modules\Shared\Forms\Fields\EditorField;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Forms\Form;

class MembershipSettingForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(MembershipSetting::class)
            ->setTemplate('customers::forms.base')
            ->setTitle('Membership Setting')
            ->add('membership_code', InputField::class, InputField::make('membership_code')->setLabel('Code')->setPlaceholder('Enter membership code...')->isRequired())
            ->add('name', InputField::class, InputField::make('name')->setLabel('Name')->setPlaceholder('Enter name...')->isRequired())
            ->add('min_points', InputField::class, InputField::make('min_points')->setLabel('Min Points')->setPlaceholder('Enter minimum points...')->setAttributes(['type' => 'number', 'min' => '0'])->isRequired())
            ->add('status', SelectField::class, SelectField::make('status')->setLabel('Status')->setOptions(BasicStatusEnum::labels())->isRequired())
            ->add('description', EditorField::class, EditorField::make('description')->setLabel('Description'));
    }

    public function createWithModel(Model $model): static
    {
        parent::createWithModel($model);
        $this->getField('membership_code')?->isReadonly();

        return $this;
    }
}
