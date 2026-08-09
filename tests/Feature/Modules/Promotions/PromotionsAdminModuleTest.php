<?php

namespace Tests\Feature\Modules\Promotions;

use App\Table\Configs\TableConfig;
use Illuminate\Support\Facades\Route;
use Modules\Promotions\Admin\Tables\CouponApplicableTable;
use Modules\Promotions\Admin\Tables\CouponRedemptionTable;
use Modules\Promotions\Admin\Tables\CouponTable;
use Modules\Promotions\Http\Admin\Controllers\CouponApplicableController;
use Modules\Promotions\Http\Admin\Controllers\CouponController;
use Modules\Promotions\Http\Admin\Controllers\CouponRedemptionController;
use Tests\TestCase;

class PromotionsAdminModuleTest extends TestCase
{
    public function test_promotions_routes_views_and_tables_are_registered(): void
    {
        $this->assertSame(CouponController::class.'@index', Route::getRoutes()->getByName('admin.coupons.index')->getActionName());
        $this->assertSame(CouponApplicableController::class.'@index', Route::getRoutes()->getByName('admin.coupon-applicables.index')->getActionName());
        $this->assertSame(CouponRedemptionController::class.'@index', Route::getRoutes()->getByName('admin.coupon-redemptions.index')->getActionName());
        $this->assertSame(CouponTable::class, app(TableConfig::class)->resolve('coupons'));
        $this->assertSame(CouponApplicableTable::class, app(TableConfig::class)->resolve('coupon-applicables'));
        $this->assertSame(CouponRedemptionTable::class, app(TableConfig::class)->resolve('coupon-redemptions'));
        $this->assertTrue(view()->exists('promotions::forms.base'));
        $this->assertTrue(view()->exists('promotions::tables.index'));
    }
}
