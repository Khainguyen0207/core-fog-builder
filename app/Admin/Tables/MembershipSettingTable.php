<?php

namespace App\Admin\Tables;

use App\Enums\BasicStatusEnum;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\MembershipSetting;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;
use Illuminate\Support\Str;

class MembershipSettingTable extends BaseTable
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(MembershipSetting::class)
            ->setName('membership-settings')
            ->setNameTable('Membership Settings')
            ->setRoute('admin.membership-settings.index')
            ->hasFilter()
            ->notHeaderAction()
            ->addColumns([
                Column::make('membership_code')->setLabel('Code'),
                Column::make('name')->setLabel('Name'),
                FormatColumn::make('min_points')
                    ->setLabel('Min Points')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return number_format($item->min_points, 0, ',', '.');
                    }),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->status->toHtml();
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
                    ->setActionUrl('admin.membership-settings.show')
                    ->setAttribute('key', 'membership_code')
                    ->hasModal(false),
            ])
            ->addFilters([
                InputField::make('name')
                    ->setName('name')
                    ->setPlaceholder('Enter Name...')
                    ->setLabel('Name'),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(BasicStatusEnum::labels()),
            ]);
    }
}
