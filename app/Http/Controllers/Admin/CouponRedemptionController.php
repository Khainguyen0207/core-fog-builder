<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Tables\CouponRedemptionTable;
use App\Http\Controllers\Controller;

class CouponRedemptionController extends Controller
{
    public function index(CouponRedemptionTable $table)
    {
        return $table->renderTable();
    }
}
