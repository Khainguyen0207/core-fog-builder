<?php

namespace Modules\Payments\Admin\Tables;

use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\BasicOperation;
use Modules\Shared\Tables\Table;

class TransactionTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Transaction::class)
            ->setName('transactions')
            ->setNameTable('Transactions')
            ->setRoute('admin.transactions.index')
            ->hasFilter()
            ->notBulkDelete()
            ->hasCheckbox(false)
            ->usingQuery(Transaction::query()->with('paymentSetting'))
            ->notHeaderAction()
            ->addColumns([
                IDColumn::make(),
                Column::make('transaction_code')->setLabel('Transaction Code'),
                FormatColumn::make('amount')
                    ->setLabel('Amount')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return number_format($item->amount).' '.$item->currency;
                    }),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->status->toHtml();
                    }),
                FormatColumn::make('created_at')
                    ->setLabel('Created At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->created_at?->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                BasicOperation::make()
                    ->setActionUrl('admin.transactions.show')
                    ->setName('btn-show')
                    ->setMethod('GET')
                    ->setAttributes([
                        'class' => 'btn btn-icon btn-sm btn-secondary text-white',
                    ])
                    ->setIcon('bx bx-show'),
            ])
            ->addFilters([
                InputField::make('transaction_code')
                    ->setName('transaction_code')
                    ->setPlaceholder('Enter Transaction Code...')
                    ->setLabel('Transaction Code'),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(TransactionStatusEnum::labels()),
            ]);
    }
}
