<?php

namespace Modules\Communications\Admin\Tables;

use App\Forms\Fields\InputField;
use App\Models\Customer;
use App\Table\Columns\Column;
use Modules\AdminUi\Tables\ModuleTable;

class SendEmailUserTable extends ModuleTable
{
    protected string $moduleView = 'communications::tables.index';

    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Customer::class)
            ->setName('send_email_users')
            ->setNameTable('Customers (Select to send email)')
            ->setRoute('admin.send-email.index')
            ->hasFilter()
            ->notHeaderAction()
            ->hasCheckbox(true)
            ->operationsColumn(false)
            ->usingQuery(
                Customer::query()
                    ->join('users', 'customers.user_id', '=', 'users.id')
                    ->select([
                        'users.id as id',
                        'customers.name',
                        'users.email',
                        'customers.phone',
                    ])
                    ->whereNotNull('users.email')
            )
            ->addColumns([
                Column::make('name')->setLabel('Customer Name'),
                Column::make('email')->setLabel('Email'),
                Column::make('phone')->setLabel('Phone'),
            ])
            ->addFilters([
                InputField::make('name')
                    ->setName('name')
                    ->setPlaceholder('Search by name...')
                    ->setLabel('Name'),
                InputField::make('email')
                    ->setName('email')
                    ->setPlaceholder('Search by email...')
                    ->setLabel('Email'),
            ]);
    }
}
