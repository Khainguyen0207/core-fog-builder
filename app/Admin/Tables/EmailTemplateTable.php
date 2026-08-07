<?php

namespace App\Admin\Tables;

use App\Forms\Fields\InputField;
use App\Models\EmailTemplate;
use App\Table\BaseTable;
use App\Table\Columns\Column;
use App\Table\Columns\FormatColumn;
use App\Table\Columns\IDColumn;
use App\Table\Operations\DeleteOperation;
use App\Table\Operations\EditOperation;
use App\Table\Operations\PreviewOperation;
use Illuminate\Support\Str;

class EmailTemplateTable extends BaseTable
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
