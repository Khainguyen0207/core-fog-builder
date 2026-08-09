<?php

use Illuminate\Support\Facades\Route;
use Modules\Communications\Http\Admin\Controllers\EmailTemplateController;
use Modules\Communications\Http\Admin\Controllers\SendEmailController;
use Modules\Communications\Http\Admin\Controllers\TelegramBotController;

Route::get('admin/email-templates/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.email-templates.preview');

Route::resource('admin/email-templates', EmailTemplateController::class)
    ->except(['create', 'store'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->names('admin.email-templates');

Route::get('admin/send-email', [SendEmailController::class, 'index'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.send-email.index');

Route::get('admin/send-email/preview/{id}', [SendEmailController::class, 'getTemplatePreview'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.send-email.preview');

Route::post('admin/send-email/send', [SendEmailController::class, 'send'])
    ->middleware(['web', 'auth', 'ip.manager'])
    ->name('admin.send-email.send');

Route::get('updated-activity', [TelegramBotController::class, 'updatedActivity'])
    ->middleware(['web', 'guest']);
