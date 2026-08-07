<?php

namespace App\Admin\Forms;

use App\Enums\BasicStatusEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\MembershipSetting;

class MembershipSettingForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(MembershipSetting::class)
            ->setTitle('Membership Setting')
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Name')
                    ->setPlaceholder('Enter name...')
                    ->isRequired()
            )
            ->add(
                'min_points',
                InputField::class,
                InputField::make('min_points')
                    ->setLabel('Min Points')
                    ->setPlaceholder('Enter minimum points...')
                    ->setAttributes(['type' => 'number', 'min' => '0'])
                    ->isRequired()
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(BasicStatusEnum::labels())
                    ->isRequired()
            )
            ->add(
                'description',
                EditorField::class,
                EditorField::make('description')
                    ->setLabel('Description')
            );
    }
}
