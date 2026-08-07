<?php

namespace App\Admin\Tables;

use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Staff;
use App\Models\StaffReview;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\BasicOperation;

class StaffReviewTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(StaffReview::class)
            ->setName('staff-reviews')
            ->setNameTable('Staff Reviews')
            ->setRoute('admin.staff-reviews.index')
            ->hasFilter()
            ->hasCheckbox(false)
            ->notBulkDelete()
            ->usingQuery(
                StaffReview::query()
                    ->with(['customer', 'staff', 'bookingService'])
            )
            ->notHeaderAction()
            ->addColumns([
                IDColumn::make(),
                FormatColumn::make('customer_id')
                    ->setLabel('Customer')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->customer?->name ?? '-';
                    }),
                FormatColumn::make('staff_id')
                    ->setLabel('Staff')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->staff?->name ?? '-';
                    }),
                FormatColumn::make('booking_service_id')
                    ->setLabel('Service')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->bookingService?->service_name ?? '-';
                    }),
                FormatColumn::make('rating')
                    ->setLabel('Rating')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $item->rating) {
                                $stars .= '<span class="icon-base bx bxs-star text-warning"></span>';
                            } else {
                                $stars .= '<span class="icon-base bx bx-star text-muted"></span>';
                            }
                        }

                        return $stars . ' <span class="text-muted">(' . $item->rating . '/5)</span>';
                    }),
                Column::make('note')->setLabel('Note'),
                FormatColumn::make('created_at')
                    ->setLabel('Created At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->created_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                BasicOperation::make()
                    ->setActionUrl('admin.staff-reviews.show')
                    ->setName('btn-show')
                    ->setMethod('GET')
                    ->setAttributes([
                        'class' => 'btn btn-icon btn-sm btn-secondary text-white',
                    ])
                    ->setIcon('bx bx-show'),
            ])
            ->addFilters([
                SelectField::make('staff_id')
                    ->setName('staff_id')
                    ->setLabel('Staff')
                    ->hasFilter()
                    ->setOptions(
                        Staff::query()
                            ->select(['id', 'name'])
                            ->get()
                            ->pluck('name', 'id')
                            ->toArray()
                    ),
                SelectField::make('rating')
                    ->setName('rating')
                    ->setLabel('Rating')
                    ->hasFilter()
                    ->setOptions([
                        '1' => '1 Star',
                        '2' => '2 Stars',
                        '3' => '3 Stars',
                        '4' => '4 Stars',
                        '5' => '5 Stars',
                    ]),
                InputField::make('created_at')
                    ->setName('created_at')
                    ->setType('date')
                    ->setPlaceholder('Created At...')
                    ->setLabel('Created At'),
            ]);
    }
}
