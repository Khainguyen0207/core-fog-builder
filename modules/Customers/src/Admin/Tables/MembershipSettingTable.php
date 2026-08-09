<?php

namespace Modules\Customers\Admin\Tables;

use App\Enums\BasicStatusEnum;
use App\Models\MembershipSetting;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class MembershipSettingTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(MembershipSetting::class)->setName('membership-settings')->setNameTable('Membership Settings')
            ->setRoute('admin.membership-settings.index')->hasFilter()
            ->addColumns([
                Column::make('membership_code')->setLabel('Code'),
                Column::make('name')->setLabel('Name'),
                FormatColumn::make('min_points')->setLabel('Min Points')->getValueUsing(function (FormatColumn $column) {
                    return number_format($column->getItem()->min_points, 0, ',', '.');
                }),
                FormatColumn::make('status')->setLabel('Status')->getValueUsing(function (FormatColumn $column) {
                    return $column->getItem()->status->toHtml();
                }),
                FormatColumn::make('updated_at')->setLabel('Updated At')->getValueUsing(function (FormatColumn $column) {
                    return $column->getItem()->updated_at->format('Y-m-d H:i:s');
                }),
            ])
            ->addOperations([
                EditOperation::make()->setActionUrl('admin.membership-settings.show')->setAttribute('key', 'membership_code')->hasModal(false),
            ])
            ->addFilters([
                InputField::make('name')->setName('name')->setPlaceholder('Enter Name...')->setLabel('Name'),
                SelectField::make('status')->setName('status')->setLabel('Status')->hasFilter()->setOptions(BasicStatusEnum::labels()),
            ]);
    }
}
