<?php

namespace Modules\Users\Admin\Forms;

use App\Enums\UserGroupRoleEnum;
use App\Models\User;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Forms\Form;

class UserForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(User::class)
            ->setTemplate('users::forms.base')
            ->setTitle('User')
            ->add(
                'email',
                InputField::class,
                InputField::make('email')
                    ->setLabel('Email')
                    ->setPlaceholder('Enter your email...')
                    ->isRequired()
            )
            ->add(
                'password',
                InputField::class,
                InputField::make('password')
                    ->setLabel('Password')
                    ->setType('password')
                    ->setPlaceholder('.....')
                    ->isRequired()
            )
            ->add(
                'password_confirmation',
                InputField::class,
                InputField::make('password_confirmation')
                    ->setLabel('Confirmation Password')
                    ->setType('password')
                    ->setPlaceholder('..........')
                    ->isRequired()
            )
            ->add(
                'group_role',
                SelectField::class,
                SelectField::make('group_role')
                    ->setLabel('Group Role')
                    ->setOptions(UserGroupRoleEnum::labels())
                    ->setDefaultValue('staff')
                    ->isRequired()
            )
            ->add(
                'is_active',
                SelectField::class,
                SelectField::make('is_active')
                    ->setLabel('Status')
                    ->setOptions([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->setDefaultValue(1)
                    ->isRequired()
            );
    }
}
