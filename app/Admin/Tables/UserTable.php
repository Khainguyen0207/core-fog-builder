<?php

namespace App\Admin\Tables;

use App\Enums\UserGroupRoleEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\User;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class UserTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(User::class)
            ->setName('users')
            ->setNameTable('Users')
            ->setRoute('admin.users.index')
            ->hasFilter()
            ->usingQuery(User::query()->with('customer'))
            ->addColumns([
                Column::make('id')->setLabel('#'),
                Column::make('email')->setLabel('Email'),
                FormatColumn::make('customer.name')
                    ->setLabel('Name')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        if ($item->customer) {
                            return sprintf(
                                '<a href="%s" class="text-info">%s</a>',
                                route('admin.customers.show', $item->customer->id),
                                $item->customer->name
                            );
                        }

                        if ($item->staff) {
                            return sprintf(
                                '<a href="%s" class="text-info">%s</a>',
                                route('admin.staffs.show', $item->staff->id),
                                $item->staff->name
                            );
                        }

                        return 'N/A';
                    }),
                FormatColumn::make('is_active')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->is_active ?
                            '<span class="badge bg-label-success">Active</span>' :
                            '<span class="badge bg-label-danger">Inactive</span>';
                    }),
                FormatColumn::make('group_role')
                    ->setLabel('Role')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->group_role->toHtml();
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.users.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.users.destroy')
                    ->setDescription('Do you want to delete user ID '),
            ])
            ->addFilters([
                InputField::make('email')
                    ->setName('email')
                    ->setPlaceholder('Enter Email...')
                    ->setLabel('Email'),
                SelectField::make('is_active')
                    ->setName('is_active')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ]),
                SelectField::make('group_role')
                    ->setName('group_role')
                    ->setLabel('Role')
                    ->hasFilter()
                    ->setOptions(UserGroupRoleEnum::labels()),
            ]);
    }
}
