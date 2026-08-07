<?php

namespace App\Http\Requests\Admin;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'exists:posts,post_id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'parent_comment_id' => ['nullable', 'exists:comments,comment_id'],
            'comment_body' => ['required', 'string'],
            'status' => ['required', 'in:approved,pending,spam'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->parent_comment_id && $this->post_id) {
                $parent = Comment::find($this->parent_comment_id);
                if ($parent && $parent->post_id != $this->post_id) {
                    $validator->errors()->add('parent_comment_id', 'The parent comment must belong to the same post.');
                }
            }
        });
    }
}
