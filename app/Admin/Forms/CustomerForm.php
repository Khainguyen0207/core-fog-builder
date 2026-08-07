<?php

namespace App\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Customer;
use App\Models\MembershipSetting;

class CustomerForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        $form = $this
            ->model(Customer::class)
            ->setTitle('Customer')
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Name')
                    ->setPlaceholder('Enter name...')
                    ->isRequired()
            )
            ->add(
                'phone',
                InputField::class,
                InputField::make('phone')
                    ->setLabel('Phone')
                    ->setPlaceholder('Enter phone...')
                    ->isRequired()
            );
        if (request()->routeIs('admin.customers.create')) {
            $form
                ->add(
                    'email',
                    InputField::class,
                    InputField::make('email')
                        ->setLabel('Email')
                        ->setPlaceholder('Enter email...')
                        ->isRequired()
                )
                ->add(
                    'password',
                    InputField::class,
                    InputField::make('password')
                        ->setLabel('Password')
                        ->setPlaceholder('Enter password...')
                        ->isRequired()
                )->add(
                    'password_confirmation',
                    InputField::class,
                    InputField::make('password_confirmation')
                        ->setLabel('Password Confirmation')
                        ->setPlaceholder('Enter password confirmation...')
                        ->isRequired()
                );
        }

        $form = $form->add(
            'membership_code',
            SelectField::class,
            SelectField::make('membership_code')
                ->setLabel('Membership')
                ->setOptions(
                    MembershipSetting::pluck('name', 'membership_code')->toArray()
                )
                ->isRequired()
        )
            ->add(
                'total_spent',
                InputField::class,
                InputField::make('total_spent')
                    ->setLabel('Total Spent')
                    ->setPlaceholder('Enter total spent...')
                    ->isRequired()
            )
            ->add(
                'note',
                InputField::class,
                InputField::make('note')
                    ->setLabel('Note')
                    ->setPlaceholder('Enter note...')
            );

        return $form;
    }
}
