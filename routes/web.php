<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['auth', 'ip.manager'],
], function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard.index'));

    Route::post('bulk-delete', 'BulkDeleteController@bulkDelete')->name('bulk-delete');

    Route::get('log-viewer', function () {
        return view('log-viewer::log-viewer.index');
    })->name('log-viewer.index');
});

Route::get('/', function () {
    return redirect()->route('admin.dashboard.index');
})->name('home');
