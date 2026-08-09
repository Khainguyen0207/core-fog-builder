<?php

namespace Modules\Cms\Admin\Tables;

use App\Enums\BasicStatusEnum;
use App\Enums\PostTypeEnum;
use App\Enums\UserGroupRoleEnum;
use App\Models\Post;
use Modules\Shared\Forms\Fields\InputField;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class PostTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Post::class)
            ->setName('posts')
            ->setNameTable('Posts')
            ->setRoute('admin.posts.index')
            ->hasFilter()
            ->usingQuery(
                Post::query()
                    ->select(['posts.*'])
                    ->with(['user', 'postView', 'user.staff', 'user.customer'])
            )
            ->addColumns([
                IDColumn::make('post_id'),
                Column::make('title')->setLabel('Title'),
                Column::make('slug')->setLabel('Slug'),
                FormatColumn::make('user_id')
                    ->setLabel('Author')
                    ->getValueUsing(function (FormatColumn $column) {
                        $user = $column->getItem()->user;

                        $name = match ($user?->group_role->getValue()) {
                            UserGroupRoleEnum::CUSTOMER => $user->customer?->name ?? '_',
                            UserGroupRoleEnum::STAFF => $user->staff?->name ?? '_',
                            default => 'Admin',
                        };

                        return sprintf('<a href="%s">%s</a>', $user ? route('admin.users.show', $user->getKey()) : '#', $name);
                    }),
                // FormatColumn::make('status')
                //     ->setLabel('Status')
                //     ->getValueUsing(function (FormatColumn $column) {
                //         $status = $column->getItem()->status;
                //         $class = $status === 'published' ? 'bg-label-success' : 'bg-label-secondary';

                //         return '<span class="badge ' . $class . '">' . ucfirst($status) . '</span>';
                //     }),
                FormatColumn::make('view_count')
                    ->setLabel('Views')
                    ->getValueUsing(function (FormatColumn $column) {
                        return $column->getItem()->postView?->view_count ?? 0;
                    }),
                FormatColumn::make('like_count')
                    ->setLabel('Likes')
                    ->getValueUsing(function (FormatColumn $column) {
                        return $column->getItem()->postView?->like_count ?? 0;
                    }),
            ])
            ->addOperations([
                EditOperation::make()
                    ->setActionUrl('admin.posts.edit')
                    ->hasModal(false)
                    ->setAttribute('key', 'post_id'),
                DeleteOperation::make()
                    ->setDataActionUrl('admin.posts.destroy')
                    ->setDescription('Do you want to delete this post?')
                    ->setAttribute('key', 'post_id'),
            ])
            ->addFilters([
                InputField::make('title')
                    ->setName('title')
                    ->setPlaceholder('Enter Title...')
                    ->setLabel('Title'),
                SelectField::make('status')
                    ->setName('status')
                    ->setLabel('Status')
                    ->hasFilter()
                    ->setOptions(BasicStatusEnum::labels()),
                SelectField::make('type')
                    ->setName('type')
                    ->setLabel('Type')
                    ->hasFilter()
                    ->setOptions(PostTypeEnum::labels()),
            ]);
    }
}
