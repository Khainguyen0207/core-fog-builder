<?php

namespace Modules\Customers\Admin\Tables;

use App\Enums\CustomerMemberShipEnum;
use App\Models\Customer;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class CustomerTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Customer::class)->setName('customers')->setNameTable('Customers')->setRoute('admin.customers.index')->hasFilter()
            ->usingQuery(Customer::query()->with('user'))
            ->addColumns([
                IDColumn::make(),
                Column::make('name')->setLabel('Name'),
                Column::make('phone')->setLabel('Phone'),
                FormatColumn::make('user.email')->setLabel('Email')->getValueUsing(function (FormatColumn $column) {
                    $item = $column->getItem();

                    return sprintf('<a href="%s" class="text-info">%s</a>', route('admin.users.show', $item->user->getKey()), $item->user?->email);
                }),
                FormatColumn::make('membership_code')->setLabel('Membership')->getValueUsing(function (FormatColumn $column) {
                    return $column->getItem()->membership_code->toHtml();
                }),
                FormatColumn::make('updated_at')->setLabel('Updated At')->getValueUsing(function (FormatColumn $column) {
                    return $column->getItem()->updated_at->format('Y-m-d H:i:s');
                }),
                FormatColumn::make('total_spent')->setLabel('Total Spent')->getValueUsing(function (FormatColumn $column) {
                    return number_format($column->getItem()->total_spent, 0, ',', '.').'đ';
                }),
            ])
            ->addOperations([
                EditOperation::make()->setActionUrl('admin.customers.show')->hasModal(false),
                DeleteOperation::make()->setDataActionUrl('admin.customers.destroy')->setDescription('Do you want to delete customer ID '),
            ])
            ->addFilters([
                InputField::make('name')->setPlaceholder('Enter Name...')->setLabel('Name'),
                InputField::make('phone')->setPlaceholder('Enter Phone...')->setLabel('Phone'),
                InputField::make('user.email')->setLabel('Email')->setPlaceholder('Enter Email...')->hasFilter(),
                SelectField::make('membership_code')->setLabel('Membership')->hasFilter()->setOptions(CustomerMemberShipEnum::labels()),
            ]);
    }
}
