<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    DataTablesServiceProvider::class,
    RouteServiceProvider::class,
];
