<?php

namespace App\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Models\Tag;

class TagForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Tag::class)
            ->setTitle('Tag')
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Name')
                    ->setPlaceholder('Enter tag name...')
                    ->isRequired()
            )
            ->add(
                'slug',
                InputField::class,
                InputField::make('slug')
                    ->setLabel('Slug')
                    ->setPlaceholder('Leave empty to auto-generate from name')
            );
    }
}
