<?php

namespace Modules\Users\Admin\Tables;

use App\Enums\UserGroupRoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class UserTable extends Table
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
            ->usingQuery(User::query()
                ->select('users.*')
                ->with('customer')
                ->whereNot('users.id', Auth::id()))
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
