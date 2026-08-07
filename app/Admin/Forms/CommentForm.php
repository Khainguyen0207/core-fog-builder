<?php

namespace App\Admin\Forms;

use App\Forms\BaseForm;
use App\Forms\Fields\EditorField;
use App\Forms\Fields\SelectField;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentForm extends BaseForm
{
    public function setup(): static
    {
        parent::setup();

        return $this
            ->model(Comment::class)
            ->setTitle('Comment')
            ->add(
                'post_id',
                SelectField::class,
                SelectField::make('post_id')
                    ->setLabel('Post')
                    ->setAttributes(['class' => 'form-control select2'])
                    ->setOptions(Post::pluck('title', 'post_id')->toArray())
                    ->isRequired()
            )
            ->add(
                'user_id',
                SelectField::class,
                SelectField::make('user_id')
                    ->setLabel('User')
                    ->setAttributes(['class' => 'form-control select2'])
                    ->setOptions(
                        User::query()
                            ->select(['id', 'email'])
                            ->get()
                            ->map(function ($user) {
                                return $user->id.' - '.$user->email;
                            })
                            ->toArray()
                    )
            )
            ->add(
                'parent_comment_id',
                SelectField::class,
                SelectField::make('parent_comment_id')
                    ->setLabel('Parent Comment')
                    ->setAttributes(['class' => 'form-control select2'])
                    ->setOptions(
                        Comment::with('post')->get()->mapWithKeys(function ($comment) {
                            $label = $comment->comment_id.' - '.\Illuminate\Support\Str::limit($comment->comment_body, 30);
                            if ($comment->post) {
                                $label = '['.$comment->post->title.'] '.$label;
                            }

                            return [$comment->comment_id => $label];
                        })->toArray()
                    )
            )
            ->add(
                'status',
                SelectField::class,
                SelectField::make('status')
                    ->setLabel('Status')
                    ->setOptions([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'spam' => 'Spam',
                    ])
                    ->setDefaultValue('pending')
                    ->isRequired()
            )
            ->add(
                'comment_body',
                EditorField::class,
                EditorField::make('comment_body')
                    ->setLabel('Comment Body')
                    ->isRequired()
            );
    }
}
