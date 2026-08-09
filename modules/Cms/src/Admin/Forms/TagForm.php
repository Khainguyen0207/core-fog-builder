<?php

namespace Modules\Cms\Admin\Forms;

use App\Models\Tag;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Form;

class TagForm extends Form
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Tag::class)
            ->setTemplate('cms::forms.base')
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
