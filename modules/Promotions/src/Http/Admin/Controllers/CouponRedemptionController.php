<?php

namespace Modules\Promotions\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use Modules\Promotions\Admin\Tables\CouponRedemptionTable;

class CouponRedemptionController extends Controller
{
    public function index(CouponRedemptionTable $table)
    {
        return $table->renderTable();
    }
}
