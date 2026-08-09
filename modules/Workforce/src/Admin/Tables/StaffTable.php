<?php

namespace Modules\Workforce\Admin\Tables;

use App\Enums\StaffLevelEnum;
use App\Models\Staff;
use Illuminate\Support\Facades\Storage;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class StaffTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Staff::class)
            ->setName('staffs')
            ->setNameTable('Staffs')
            ->setRoute('admin.staffs.index')
            ->hasFilter()
            ->usingQuery(
                Staff::query()
                    ->with('user')
            )
            ->addColumns([
                IDColumn::make(),
                Column::make('staff_code')->setLabel('Staff Code'),
                FormatColumn::make('name')->setLabel('Name')->getValueUsing(function (FormatColumn $column) {
                    $item = $column->getItem();

                    return view('shared::layouts.partials.ui-avatar-name', [
                        'image' => Storage::url($item->avatar),
                        'name' => $item->name,
                        'subname' => 'Hi! I\'m '.$item->name,
                    ]);
                }),
                Column::make('phone')->setLabel('Phone'),
                FormatColumn::make('level')
                    ->setLabel('Level')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->level->toHtml();
                    }),
                FormatColumn::make('is_active')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->is_active ?
                            '<span class="badge bg-label-success">Active</span>' :
                            '<span class="badge bg-label-danger">Inactive</span>';
                    }),
                FormatColumn::make('updated_at')
                    ->setLabel('Updated At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->updated_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.staffs.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.staffs.destroy')
                    ->setDescription('Do you want to delete staff ID '),
            ])
            ->addFilters([
                InputField::make('staff_code')
                    ->setName('staff_code')
                    ->setPlaceholder('Enter Staff Code...')
                    ->setLabel('Staff Code'),
                SelectField::make('level')
                    ->setName('level')
                    ->setLabel('Level')
                    ->hasFilter()
                    ->setOptions(StaffLevelEnum::labels()),
                SelectField::make('is_active')
                    ->setName('is_active')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ]),
            ]);
    }
}
