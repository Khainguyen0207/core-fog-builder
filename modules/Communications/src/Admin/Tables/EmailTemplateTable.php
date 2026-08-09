<?php

namespace Modules\Communications\Admin\Tables;

use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Operations\PreviewOperation;
use Modules\Shared\Tables\Table;

class EmailTemplateTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(EmailTemplate::class)
            ->setName('email_templates')
            ->setNameTable('Templates')
            ->setRoute('admin.email-templates.index')
            ->notHeaderAction()
            ->hasFilter()
            ->addColumns([
                IDColumn::make(),
                Column::make('name')->setLabel('Name'),
                FormatColumn::make('description')
                    ->setLabel('Description')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return Str::limit(strip_tags($item->description), 50);
                    }),
                FormatColumn::make('updated_at')
                    ->setLabel('Updated At')
                    ->getValueUsing(function (FormatColumn $column) {
                        $item = $column->getItem();

                        return $item->updated_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->addOperations([
                PreviewOperation::make()
                    ->setActionUrl('admin.email-templates.preview'),
                EditOperation::make()
                    ->setActionUrl('admin.email-templates.show')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.email-templates.destroy')
                    ->setDescription('Do you want to delete template ID '),
            ])
            ->addFilters([
                InputField::make('name')
                    ->setName('name')
                    ->setPlaceholder('Search by name...')
                    ->setLabel('Name'),
            ]);
    }
}
