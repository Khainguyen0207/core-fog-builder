<?php

namespace Modules\Cms\Admin\Forms;

use App\Models\BlogCategory;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Form;

class BlogCategoryForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(BlogCategory::class)
            ->setTemplate('cms::forms.base')
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
