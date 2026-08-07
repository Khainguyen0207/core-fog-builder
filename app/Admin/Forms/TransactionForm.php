<?php

namespace App\Admin\Forms;

use App\Enums\TransactionStatusEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Transaction;

class TransactionForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Transaction::class)
            ->setTitle('Transaction')
            ->setView('admin.forms.transaction.details')
            ->add(
                'id',
                InputField::class,
                InputField::make('id')
                    ->setLabel('ID')
                    ->isReadonly()
            )
            ->add(
                'transaction_code',
                InputField::class,
                InputField::make('transaction_code')
                    ->setLabel('Transaction Code')
                    ->isReadonly()
            )
            ->add(
                'token',
                InputField::class,
                InputField::make('token')
                    ->setLabel('Token')
                    ->isReadonly()
            )
            ->add(
                'customer_name',
                InputField::class,
                InputField::make('customer_name')
                    ->setLabel('Customer Name')
                    ->isReadonly()
            )
            ->add(
                'customer_phone',
                InputField::class,
                InputField::make('customer_phone')
                    ->setLabel('Customer Phone')
                    ->isReadonly()
            )
            ->add(
                'payment_method',
                InputField::class,
                InputField::make('payment_method')
                    ->setLabel('Payment Method')
                    ->isReadonly()
            )
            ->add(
                'provider_code',
                InputField::class,
                InputField::make('provider_code')
                    ->setLabel('Provider Code')
                    ->setPlaceholder('Enter provider code...')
            )
            ->add(
                'amount',
                InputField::class,
                InputField::make('amount')
                    ->setLabel('Amount')
                    ->setType('number')
                    ->isReadonly()
            )
            ->add(
                'currency',
                InputField::class,
                InputField::make('currency')
                    ->setLabel('Currency')
                    ->isReadonly()
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(TransactionStatusEnum::labels())
                    ->setSpan(2)
            )
            ->add(
                'response',
                EditorField::class,
                EditorField::make('response')
                    ->setLabel('Response (JSON)')
                    ->setSpan(2)
            )
            ->add(
                'created_at',
                InputField::class,
                InputField::make('created_at')
                    ->setLabel('Created At')
                    ->isReadonly()
            )
            ->add(
                'updated_at',
                InputField::class,
                InputField::make('updated_at')
                    ->setLabel('Updated At')
                    ->isReadonly()
            );
    }
}
