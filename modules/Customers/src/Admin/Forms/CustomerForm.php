<?php

namespace Modules\Customers\Admin\Forms;

use App\Models\Customer;
use App\Models\MembershipSetting;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Forms\Form;

class CustomerForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        $form = $this
            ->model(Customer::class)
            ->setTemplate('customers::forms.base')
            ->setTitle('Customer')
            ->add('name', InputField::class, InputField::make('name')->setLabel('Name')->setPlaceholder('Enter name...')->isRequired())
            ->add('phone', InputField::class, InputField::make('phone')->setLabel('Phone')->setPlaceholder('Enter phone...')->isRequired());

        if (request()->routeIs('admin.customers.create')) {
            $form
                ->add('email', InputField::class, InputField::make('email')->setLabel('Email')->setPlaceholder('Enter email...')->isRequired())
                ->add('password', InputField::class, InputField::make('password')->setLabel('Password')->setPlaceholder('Enter password...')->isRequired())
                ->add('password_confirmation', InputField::class, InputField::make('password_confirmation')->setLabel('Password Confirmation')->setPlaceholder('Enter password confirmation...')->isRequired());
        }

        return $form
            ->add('membership_code', SelectField::class, SelectField::make('membership_code')->setLabel('Membership')->setOptions(MembershipSetting::pluck('name', 'membership_code')->toArray())->isRequired())
            ->add('total_spent', InputField::class, InputField::make('total_spent')->setLabel('Total Spent')->setPlaceholder('Enter total spent...')->isRequired())
            ->add('note', InputField::class, InputField::make('note')->setLabel('Note')->setPlaceholder('Enter note...'));
    }
}
