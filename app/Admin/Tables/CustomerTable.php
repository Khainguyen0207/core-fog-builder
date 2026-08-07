<?php

namespace App\Admin\Tables;

use App\Enums\CustomerMemberShipEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Customer;
use App\Models\MembershipSetting;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class CustomerTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Customer::class)
            ->setName('customers')
            ->setNameTable('Customers')
            ->setRoute('admin.customers.index')
            ->hasFilter()
            ->usingQuery(Customer::query()->with('user'))
            ->addColumns([
                IDColumn::make(),
                Column::make('name')->setLabel('Name'),
                Column::make('phone')->setLabel('Phone'),
                FormatColumn::make('user.email')
                    ->setLabel('Email')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return sprintf('<a href="%s" class="text-info">%s</a>', route('admin.users.show', $item->user->getKey()), $item->user?->email);
                    }),
                FormatColumn::make('membership_code')
                    ->setLabel('Membership')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->membership_code->toHtml();
                    }),
                FormatColumn::make('updated_at')
                    ->setLabel('Updated At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->updated_at->format('Y-m-d H:i:s');
                    }),
                FormatColumn::make('total_spent')
                    ->setLabel('Total Spent')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return number_format($item->total_spent, 0, ',', '.') . 'đ';
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.customers.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.customers.destroy')
                    ->setDescription('Do you want to delete customer ID '),
            ])
            ->addFilters([
                InputField::make('name')
                    ->setPlaceholder('Enter Name...')
                    ->setLabel('Name'),
                InputField::make('phone')
                    ->setPlaceholder('Enter Phone...')
                    ->setLabel('Phone'),
                InputField::make('user.email')
                    ->setLabel('Email')
                    ->setPlaceholder('Enter Email...')
                    ->hasFilter(),
                SelectField::make('membership_code')
                    ->setLabel('Membership')
                    ->hasFilter()
                    ->setOptions(CustomerMemberShipEnum::labels()),
            ]);
    }
}
