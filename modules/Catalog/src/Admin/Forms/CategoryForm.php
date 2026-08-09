<?php

namespace Modules\Catalog\Admin\Forms;

use App\Models\Category;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Form;

class CategoryForm extends Form
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
