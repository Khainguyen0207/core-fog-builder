<?php

namespace App\Admin\Tables;

use App\Enums\BookingStatusEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Booking;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\BasicOperation;

class BookingTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Booking::class)
            ->setName('bookings')
            ->setNameTable('Bookings')
            ->setRoute('admin.bookings.index')
            ->hasFilter()
            ->hasCheckbox(false)
            ->usingQuery(
                Booking::query()
                    ->with('assignedStaff')
            )
            ->notHeaderAction()
            ->addColumns([
                IDColumn::make(),
                Column::make('booking_code')->setLabel('Booking Code'),
                Column::make('customer_name')->setLabel('Customer Name'),
                Column::make('customer_phone')->setLabel('Customer Phone'),
                FormatColumn::make('scheduled_start')
                    ->setLabel('Scheduled Start')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->scheduled_start->format('Y-m-d H:i:s');
                    }),
                FormatColumn::make('estimated_end')
                    ->setLabel('End')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->estimated_end->format('Y-m-d H:i:s');
                    }),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->status->toHtml();
                    }),
            ])
            ->addOperations([
                BasicOperation::make()
                    ->setActionUrl('admin.bookings.show')
                    ->setName('btn-show')
                    ->setMethod('GET')
                    ->setAttributes([
                        'class' => 'btn btn-icon btn-sm btn-secondary text-white',
                    ])
                    ->setIcon('bx bx-show'),
            ])
            ->addFilters([
                InputField::make('booking_code')
                    ->setName('booking_code')
                    ->setPlaceholder('Enter Booking Code...')
                    ->setLabel('Booking Code'),
                InputField::make('customer_name')
                    ->setName('customer_name')
                    ->setPlaceholder('Enter Customer Name...')
                    ->setLabel('Customer Name'),
                InputField::make('customer_phone')
                    ->setName('customer_phone')
                    ->setPlaceholder('Enter Customer Phone...')
                    ->setLabel('Customer Phone'),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(BookingStatusEnum::labels()),
                InputField::make('scheduled_start')
                    ->setName('scheduled_start')
                    ->setType('date')
                    ->setPlaceholder('Scheduled Start...')
                    ->setLabel('Scheduled Start'),
            ]);
    }
}
