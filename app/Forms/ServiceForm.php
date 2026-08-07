<?php

namespace App\Forms;

use App\Enums\BaseStatusEnum;
use App\Enums\TimeUnitEnum;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\Category;
use App\Models\Service;

class ServiceForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Service::class)
            ->setTitle('Service')
            ->add(
                'title',
                InputField::class,
                InputField::make('title')
                    ->setLabel('Title')
                    ->setPlaceholder('Enter title...')
                    ->isRequired()
            )
            ->add(
                'subtitle',
                InputField::class,
                InputField::make('subtitle')
                    ->setLabel('Subtitle')
                    ->setPlaceholder('Enter subtitle...')
            )
            ->add(
                'description',
                EditorField::class,
                EditorField::make('description')
                    ->setSpan(2)
                    ->setLabel('Description')
            )
            ->add(
                'category_id',
                SelectField::class,
                SelectField::make('category_id')
                    ->setLabel('Category')
                    ->setOptions(Category::query()->pluck('name', 'id')->toArray())
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions(BaseStatusEnum::labels())
                    ->setDefaultValue(BaseStatusEnum::ENABLED)
                    ->isRequired()
            )
            ->add(
                'price',
                InputField::class,
                InputField::make('price')
                    ->setLabel('Price')
                    ->setType('number')
                    ->setPlaceholder('Enter price...')
                    ->isRequired()
            )
            ->add(
                'time_do',
                InputField::class,
                InputField::make('time_do')
                    ->setLabel('Time Do')
                    ->setType('number')
                    ->setPlaceholder('Enter time do...')
                    ->isRequired()
            )
            ->add(
                'time_unit',
                SelectField::class,
                SelectField::make('time_unit')
                    ->setLabel('Time Unit')
                    ->setOptions(TimeUnitEnum::labels())
                    ->setDefaultValue(TimeUnitEnum::MINUTE)
                    ->isRequired()
            )
            ->add(
                'priority',
                InputField::class,
                InputField::make('priority')
                    ->setLabel('Priority')
                    ->setType('number')
                    ->setDefaultValue(0)
                    ->setPlaceholder('Enter priority...')
            );
    }
}
