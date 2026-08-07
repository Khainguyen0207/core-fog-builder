<?php

namespace App\Admin\Forms;

use App\Enums\StaffLevelEnum;
use App\Enums\UserGroupRoleEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Table\HeaderActions\CreateHeaderAction;

class StaffForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        $this
            ->model(Staff::class)
            ->setTitle('Staff')

            ->add(
                'staff_code',
                InputField::class,
                InputField::make('staff_code')
                    ->setLabel('Staff Code')
                    ->setPlaceholder('Enter staff code...')
                    ->isRequired()
            )
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Staff Name')
                    ->setPlaceholder('Enter staff name...')
                    ->isRequired()
            )
            ->add(
                'phone',
                InputField::class,
                InputField::make('phone')
                    ->setLabel('Phone')
                    ->setPlaceholder('Enter phone number...')
                    ->isRequired()
            )
        ;

        if (request()->routeIs('admin.staffs.create')) {
            $this->add(
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
                )
                ->add(
                    'password_confirmation',
                    InputField::class,
                    InputField::make('password_confirmation')
                        ->setLabel('Password Confirmation')
                        ->setPlaceholder('Enter password confirmation...')
                        ->isRequired()
                )
            ;
        }

        return $this
            ->add(
                'level',
                SelectField::class,
                SelectField::make('level')
                    ->setLabel('Level')
                    ->setOptions(StaffLevelEnum::labels())
                    ->isRequired()
            )
            ->add(
                'is_active',
                SelectField::class,
                SelectField::make('is_active')
                    ->setLabel('Is Active')
                    ->helperText(sprintf('If inactive, max staff active will be decreased! <br> Setup max active staff in <a href="%s">here</a>', route('admin.settings.active-staff.index')))
                    ->setOptions([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->setDefaultValue('1')
            )
            ->add(
                'service_ids',
                SelectField::class,
                SelectField::make('service_ids')
                    ->setLabel('Skills')
                    ->setOptions(Service::query()->pluck('title', 'id')->toArray())
                    ->hasMultiple(true)
                    ->setValue($this->getModel()?->services?->pluck('id')->toArray() ?? [])
            )
            ->add(
                'salary',
                InputField::class,
                InputField::make('salary')
                    ->setLabel('Salary')
                    ->setType('number')
                    ->setPlaceholder('Enter salary...')
                    ->isRequired()
            )
            ->add(
                'joined_at',
                InputField::class,
                InputField::make('joined_at')
                    ->setLabel('Joined At')
                    ->setType('datetime-local')
                    ->isRequired()
            )
            ->add(
                'resigned_at',
                InputField::class,
                InputField::make('resigned_at')
                    ->setLabel('Resigned At')
                    ->setType('datetime-local')
            )
            ->add(
                'note',
                EditorField::class,
                EditorField::make('note')
                    ->setType('textarea')
                    ->setLabel('Note')
            )
            ->add(
                'avatar',
                InputField::class,
                InputField::make('avatar')
                    ->isPreview()
                    ->setAccept('image/*')
                    ->setType('file')
                    ->setLabel('Avatar')
            );
    }
}
