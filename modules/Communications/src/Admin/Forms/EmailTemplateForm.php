<?php

namespace Modules\Communications\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Models\EmailTemplate;

class EmailTemplateForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(EmailTemplate::class)
            ->setTemplate('communications::forms.base')
            ->setTitle('Email Template')
            ->hasFile(true)
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Template Name')
                    ->setPlaceholder('Enter template name...')
                    ->isRequired()
            )
            ->add(
                'description',
                EditorField::class,
                EditorField::make('description')
                    ->setType('textarea')
                    ->setLabel('Description')
            )
            ->add(
                'file_html',
                InputField::class,
                InputField::make('file_html')
                    ->setType('file')
                    ->setAccept('.html,text/html')
                    ->isPreview(false)
                    ->setLabel('Upload HTML File')
                    ->helperText('<p>Upload a .html file to replace the current template content.</p>
                    <p>Build email template here: <a href="https://email-template-builder-three-lovat.vercel.app/" target="_blank">https://email-template-builder-three-lovat.vercel.app/</a>.</p>
                    <p>You can use variables like: {{customer_name}}, {{customer_email}}, {{customer_phone}}, {{membership_code}}</p>')
            );
    }
}
