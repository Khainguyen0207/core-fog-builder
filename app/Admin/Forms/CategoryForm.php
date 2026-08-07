<?php

namespace App\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\InputField;
use App\Models\Category;

class CategoryForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Category::class)
            ->setTitle('Category')
            ->add(
                'name',
                InputField::class,
                InputField::make('name')
                    ->setLabel('Name')
                    ->setPlaceholder('Enter name...')
                    ->isRequired()
            );
    }
}
