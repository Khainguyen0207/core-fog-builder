<?php

namespace App\Admin\Tables;

use App\Enums\BookingStatusEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Service;
use App\Models\Staff;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;

class BookingServiceTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(BookingService::class)
            ->setName('booking-services')
            ->setNameTable('Booking Services')
            ->setRoute('admin.booking-services.index')
            ->hasFilter()
            ->usingQuery(
                BookingService::query()
                    ->select(['id', 'booking_id', 'service_id', 'service_name', 'price', 'duration', 'status', 'assigned_staff_id', 'updated_at'])
                    ->with(['booking', 'service', 'assignedStaff'])
            )
            ->addColumns([
                IDColumn::make(),
                FormatColumn::make('booking')
                    ->setLabel('Booking')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->booking ? $item->booking->booking_code : '-';
                    }),
                FormatColumn::make('service')
                    ->setLabel('Service')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->service ? $item->service->title : $item->service_name;
                    }),
                Column::make('service_name')->setLabel('Service Name'),
                Column::make('price')->setLabel('Price'),
                Column::make('duration')->setLabel('Duration'),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->status->toHtml();
                    }),
                FormatColumn::make('assigned_staff')
                    ->setLabel('Assigned Staff')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();
                        if ($item->assignedStaff && $item->assignedStaff->user) {
                            return $item->assignedStaff->user->name;
                        }

                        return '-';
                    }),
                Column::make('updated_at')->setLabel('Updated At'),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.booking-services.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.booking-services.destroy')
                    ->setDescription('Do you want to delete booking service ID '),
            ])
            ->addFilters([
                InputField::make('service_name')
                    ->setName('service_name')
                    ->setPlaceholder('Enter Service Name...')
                    ->setLabel('Service Name'),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->setOptions(BookingStatusEnum::labels()),
                SelectField::make('booking_id')
                    ->setName('booking_id')
                    ->setLabel('Booking')
                    ->setOptions(
                        Booking::query()->pluck('booking_code', 'id')->toArray()
                    ),
                SelectField::make('service_id')
                    ->setName('service_id')
                    ->setLabel('Service')
                    ->setOptions(
                        Service::query()->pluck('title', 'id')->toArray()
                    ),
                SelectField::make('assigned_staff_id')
                    ->setName('assigned_staff_id')
                    ->setLabel('Assigned Staff')
                    ->setOptions(
                        Staff::query()
                            ->with('user')
                            ->get()
                            ->mapWithKeys(function ($staff) {
                                $name = $staff->user ? $staff->user->name : $staff->staff_code;

                                return [$staff->id => $name];
                            })
                            ->toArray()
                    ),
            ]);
    }
}
