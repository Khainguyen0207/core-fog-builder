<?php

use Illuminate\Support\Facades\Route;
use Modules\Cms\Http\Admin\Controllers\BlogCategoryController;
use Modules\Cms\Http\Admin\Controllers\CommentController;
use Modules\Cms\Http\Admin\Controllers\PostController;
use Modules\Cms\Http\Admin\Controllers\TagController;

Route::resource('admin/posts', PostController::class)
    ->middleware(['web', 'plugin:figure-admin/cms', 'auth', 'ip.manager'])
    ->names('admin.posts');

Route::resource('admin/blog-categories', BlogCategoryController::class)
    ->middleware(['web', 'plugin:figure-admin/cms', 'auth', 'ip.manager'])
    ->names('admin.blog-categories');

Route::resource('admin/tags', TagController::class)
    ->middleware(['web', 'plugin:figure-admin/cms', 'auth', 'ip.manager'])
    ->names('admin.tags');

Route::resource('admin/comments', CommentController::class)
    ->middleware(['web', 'plugin:figure-admin/cms', 'auth', 'ip.manager'])
    ->names('admin.comments');
