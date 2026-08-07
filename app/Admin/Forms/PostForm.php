<?php

namespace App\Admin\Forms;

use App\Enums\PostPriorityLevelEnum;
use App\Enums\PostTypeEnum;
use App\Enums\UserGroupRoleEnum;
use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\InputField;
use App\Forms\Fields\SelectField;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\User;

class PostForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Post::class)
            ->setTitle('Post')
            ->add(
                'user_id',
                SelectField::class,
                SelectField::make('user_id')
                    ->setLabel('Author')
                    ->setAttributes(['class' => 'form-control select2'])
                    ->setOptions(
                        User::query()
                            ->where('group_role', UserGroupRoleEnum::ADMIN)
                            ->pluck('email', 'id')->toArray()
                    )
            )
            ->add(
                'title',
                InputField::class,
                InputField::make('title')
                    ->setLabel('Title')
                    ->setPlaceholder('Enter title...')
                    ->isRequired()
            )
            ->add(
                'slug',
                InputField::class,
                InputField::make('slug')
                    ->setLabel('Slug')
                    ->setPlaceholder('Leave empty to auto-generate from title')
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->setDefaultValue('published')
                    ->isRequired()
            )
            ->add(
                'type',
                SelectField::class,
                SelectField::make('type')
                    ->setLabel('Type')
                    ->setOptions(PostTypeEnum::labels())
                    ->setDefaultValue(PostTypeEnum::COMMUNITY)
                    ->isRequired()
            )
            ->add(
                'priority_level',
                SelectField::class,
                SelectField::make('priority_level')
                    ->setLabel('Priority Level')
                    ->setDefaultValue(PostPriorityLevelEnum::NORMAL)
                    ->setOptions(PostPriorityLevelEnum::labels())
            )
            ->add(
                'blog_categories',
                SelectField::class,
                SelectField::make('blog_categories')
                    ->setLabel('Categories')
                    ->hasMultiple()
                    ->setAttributes(['class' => 'form-control', 'multiple' => 'multiple'])
                    ->setOptions(BlogCategory::query()->pluck('name', 'category_id')
                        ->toArray())
            )
            ->add(
                'image',
                InputField::class,
                InputField::make('image')
                    ->setLabel('Image')
                    ->setType('file')
                    ->setAccept('image/*')
                    ->isRequired()
            )
            ->add(
                'body',
                EditorField::class,
                EditorField::make('body')
                    ->setLabel('Content')
                    ->isRequired()
            );
    }
}
