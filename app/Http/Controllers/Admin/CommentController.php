<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\CommentForm;
use App\Admin\Tables\CommentTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(CommentTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return CommentForm::make()->renderForm();
    }   

    public function store(CommentRequest $request)
    {
        Comment::create($request->validated());

        return redirect()->route('admin.comments.index')->with('success', 'Comment created successfully.');
    }

    public function show(Comment $comment)
    {
        return CommentForm::make()->createWithModel($comment)->renderForm();
    }

    public function edit(Comment $comment)
    {
        return CommentForm::make()->createWithModel($comment)->renderForm();
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return redirect(request()->input('_previous_url') ?? route('admin.comments.index'))->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['error' => false, 'message' => 'Comment deleted successfully']);
    }
}
