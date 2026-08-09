<?php

namespace Modules\Cms\Admin\Tables;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Str;
use Modules\Shared\Forms\Fields\SelectField;
use Modules\Shared\Tables\Columns\Column;
use Modules\Shared\Tables\Columns\FormatColumn;
use Modules\Shared\Tables\Columns\IDColumn;
use Modules\Shared\Tables\Operations\DeleteOperation;
use Modules\Shared\Tables\Operations\EditOperation;
use Modules\Shared\Tables\Table;

class CommentTable extends Table
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->setModel(Comment::class)
            ->setName('comments')
            ->setNameTable('Comments')
            ->setRoute('admin.comments.index')
            ->hasFilter()
            ->usingQuery(
                Comment::query()
                    ->select(['comments.*'])
                    ->with(['post', 'user'])
            )
            ->addColumns([
                IDColumn::make('comment_id'),
                FormatColumn::make('post_id')
                    ->setLabel('Post')
                    ->getValueUsing(fn ($col) => Str::limit($col->getItem()->post?->title ?? '-', 30)),
                FormatColumn::make('user_id')
                    ->setLabel('User')
                    ->getValueUsing(fn ($col) => $col->getItem()->user?->email ?? 'Guest'),
                Column::make('parent_comment_id')->setLabel('Parent ID'),
                FormatColumn::make('status')
                    ->setLabel('Status')
                    ->getValueUsing(function (FormatColumn $column) {
                        $status = $column->getItem()->status;
                        $color = match ($status) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'spam' => 'danger',
                            default => 'secondary'
                        };

                        return '<span class="badge bg-label-'.$color.'">'.ucfirst($status).'</span>';
                    }),
                Column::make('created_at')->setLabel('Created At'),
            ])
            ->addOperations([
                EditOperation::make()->setActionUrl('admin.comments.edit')
                    ->setAttribute('key', 'comment_id')
                    ->hasModal(false),
                DeleteOperation::make()
                    ->setAttribute('key', 'comment_id')
                    ->setDataActionUrl('admin.comments.destroy')->setDescription('Delete comment?'),
            ])
            ->addFilters([
                SelectField::make('status')->setName('status')->setLabel('Status')
                    ->setOptions(['approved' => 'Approved', 'pending' => 'Pending', 'spam' => 'Spam']),
                SelectField::make('post_id')->setName('post_id')->setLabel('Post')
                    ->setOptions(Post::pluck('title', 'post_id')->toArray()),
            ]);
    }
}
