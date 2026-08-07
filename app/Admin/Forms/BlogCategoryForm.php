<?php

namespace App\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Models\BlogCategory;

class BlogCategoryForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(BlogCategory::class)
            ->setTitle('Blog Category')
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Name')
                    ->setPlaceholder('Enter category name...')
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
