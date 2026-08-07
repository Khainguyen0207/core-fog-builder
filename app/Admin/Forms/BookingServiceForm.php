<?php

namespace App\Admin\Forms;

use App\Enums\BookingStatusEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Service;
use App\Models\Staff;

class BookingServiceForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(BookingService::class)
            ->setTitle('Booking Service')
            ->add(
                'booking_id',
                SelectField::class,
                SelectField::make('booking_id')
                    ->setLabel('Booking')
                    ->setOptions(
                        Booking::query()->pluck('booking_code', 'id')->toArray()
                    )
                    ->isRequired()
            )
            ->add(
                'service_id',
                SelectField::class,
                SelectField::make('service_id')
                    ->setLabel('Service')
                    ->setOptions(
                        Service::query()->pluck('title', 'id')->toArray()
                    )
                    ->isRequired()
            )
            ->add(
                'service_name',
                InputField::class,
                InputField::make('service_name')
                    ->setLabel('Service Name')
                    ->setPlaceholder('Enter service name...')
                    ->isRequired()
            )
            ->add(
                'price',
                InputField::class,
                InputField::make('price')
                    ->setLabel('Price')
                    ->setType('number')
                    ->setPlaceholder('Enter price...')
                    ->isRequired()
            )
            ->add(
                'duration',
                InputField::class,
                InputField::make('duration')
                    ->setLabel('Duration')
                    ->setType('number')
                    ->setPlaceholder('Enter duration...')
                    ->isRequired()
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(BookingStatusEnum::labels())
                    ->isRequired()
            )
            ->add(
                'assigned_staff_id',
                SelectField::class,
                SelectField::make('assigned_staff_id')
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
                    )
            )
            ->add(
                'note',
                EditorField::class,
                EditorField::make('note')
                    ->setLabel('Note')
            )
            ->add(
                'started_at',
                InputField::class,
                InputField::make('started_at')
                    ->setLabel('Started At')
                    ->setType('datetime-local')
                    ->isRequired()
            )
            ->add(
                'finished_at',
                InputField::class,
                InputField::make('finished_at')
                    ->setLabel('Finished At')
                    ->setType('datetime-local')
            );
    }
}
