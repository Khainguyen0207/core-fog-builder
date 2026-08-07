<?php

namespace App\Admin\Forms;

use App\Enums\BookingStatusEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Booking;

class BookingForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Booking::class)
            ->setTitle('Booking')
            ->setView('admin.forms.booking.details')
            ->add(
                'booking_code',
                InputField::class,
                InputField::make('booking_code')
                    ->setLabel('Booking Code')
                    ->setPlaceholder('Enter booking code...')
            )
            ->add(
                'customer_id',
                SelectField::class,
                SelectField::make('customer_id')
                    ->setLabel('Customer')
                    ->setAttributes(['class' => 'form-control select2'])
                    ->setOptions(
                        \App\Models\Customer::query()
                            ->select(['id', 'name', 'phone'])
                            ->get()
                            ->map(function ($c) {
                                return $c->name . ' - ' . $c->phone;
                            })
                            ->toArray()
                    )
            )
            ->add(
                'customer_name',
                InputField::class,
                InputField::make('customer_name')
                    ->setLabel('Customer Name')
                    ->setPlaceholder('Enter customer name...')
            )
            ->add(
                'customer_phone',
                InputField::class,
                InputField::make('customer_phone')
                    ->setLabel('Customer Phone')
                    ->setPlaceholder('Enter customer phone...')
            )
            ->add(
                'notify_email',
                InputField::class,
                InputField::make('notify_email')
                    ->setLabel('Notify Email')
                    ->setPlaceholder('Enter email for notification...')
            )
            ->add(
                'bike_type',
                SelectField::class,
                SelectField::make('bike_type')
                    ->setLabel('Bike Type')
                    ->setOptions([
                        "manual" => "Xe số",
                        "scooter" => "Xe tay ga",
                        "sport" => "Xe côn tay",
                        "bigbike" => "Xe phân khối lớn",
                        "electric" => "Xe điện"
                    ])
            )
            ->add(
                'plate_number',
                InputField::class,
                InputField::make('plate_number')
                    ->setLabel('Plate Number')
                    ->setPlaceholder('Enter plate number...')
            )
            ->add(
                'scheduled_start',
                InputField::class,
                InputField::make('scheduled_start')
                    ->setLabel('Scheduled Start')
                    ->setType('datetime-local')
                    ->isRequired()
            )
            ->add(
                'estimated_end',
                InputField::class,
                InputField::make('estimated_end')
                    ->setLabel('Estimated End')
                    ->setType('datetime-local')
            )
            ->add(
                'actual_start',
                InputField::class,
                InputField::make('actual_start')
                    ->setLabel('Actual Start')
                    ->setType('datetime-local')
            )
            ->add(
                'actual_end',
                InputField::class,
                InputField::make('actual_end')
                    ->setLabel('Actual End')
                    ->setType('datetime-local')
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(BookingStatusEnum::labels())
                    ->setSpan(2)
            )
            ->add(
                'payment_method',
                SelectField::class,
                SelectField::make('payment_method')
                    ->setLabel('Payment Method')
            )
            ->add(
                'transaction_code',
                SelectField::class,
                SelectField::make('transaction_code')
                    ->setLabel('Transaction Code')
            )
            ->add(
                'price',
                InputField::class,
                InputField::make('price')
                    ->setLabel('Giá gốc')
                    ->setType('number')
            )
            ->add(
                'discount',
                InputField::class,
                InputField::make('discount')
                    ->setLabel('Giảm giá')
                    ->setType('number')
            )
            ->add(
                'total_price',
                InputField::class,
                InputField::make('total_price')
                    ->setLabel('Tổng thanh toán')
                    ->setType('number')
            )
            ->add(
                'coupon_code',
                InputField::class,
                InputField::make('coupon_code')
                    ->setLabel('Mã giảm giá')
            )
            ->add(
                'total_duration',
                InputField::class,
                InputField::make('total_duration')
                    ->setLabel('Total Duration')
                    ->setType('number')
                    ->setPlaceholder('Enter total duration...')
            )
            ->add(
                'note',
                EditorField::class,
                EditorField::make('note')
                    ->setLabel('Note')
            )
            ->add(
                'created_at',
                InputField::class,
                InputField::make('created_at')
                    ->setLabel('Created At')
            )
            ->add(
                'updated_at',
                InputField::class,
                InputField::make('updated_at')
                    ->setLabel('Updated At')
            );
    }
}
