<?php

namespace App\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\StaffReview;

class StaffReviewForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(StaffReview::class)
            ->setTitle('Staff Review')
            ->setView('admin.forms.staff-review.details')
            ->add(
                'customer_id',
                SelectField::class,
                SelectField::make('customer_id')
                    ->setLabel('Customer')
                    ->setAttributes(['class' => 'form-control', 'disabled' => 'disabled'])
                    ->setOptions(
                        Customer::query()
                            ->select(['id', 'name', 'phone'])
                            ->get()
                            ->mapWithKeys(function ($c) {
                                return [$c->id => $c->name . ' - ' . $c->phone];
                            })
                            ->toArray()
                    )
            )
            ->add(
                'staff_id',
                SelectField::class,
                SelectField::make('staff_id')
                    ->setLabel('Staff')
                    ->setAttributes(['class' => 'form-control', 'disabled' => 'disabled'])
                    ->setOptions(
                        Staff::query()
                            ->select(['id', 'name', 'staff_code'])
                            ->get()
                            ->mapWithKeys(function ($s) {
                                return [$s->id => $s->staff_code . ' - ' . $s->name];
                            })
                            ->toArray()
                    )
            )
            ->add(
                'booking_service_id',
                InputField::class,
                InputField::make('booking_service_id')
                    ->setLabel('Booking Service ID')
                    ->setAttributes(['readonly' => 'readonly'])
            )
            ->add(
                'rating',
                InputField::class,
                InputField::make('rating')
                    ->setLabel('Rating')
                    ->setType('number')
                    ->setAttributes(['readonly' => 'readonly', 'min' => 1, 'max' => 5])
            )
            ->add(
                'note',
                EditorField::class,
                EditorField::make('note')
                    ->setType('textarea')
                    ->setLabel('Note')
            )
            ->add(
                'created_at',
                InputField::class,
                InputField::make('created_at')
                    ->setLabel('Created At')
                    ->setType('datetime-local')
                    ->setAttributes(['readonly' => 'readonly'])
            );
    }
}
