<?php

namespace Modules\Promotions\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Promotions\Admin\Tables\CouponApplicableTable;
use Modules\Promotions\Admin\Tables\CouponRedemptionTable;
use Modules\Promotions\Admin\Tables\CouponTable;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;

class PromotionsServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'promotions');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('coupons', CouponTable::class);
        $tables->register('coupon-applicables', CouponApplicableTable::class);
        $tables->register('coupon-redemptions', CouponRedemptionTable::class);
        $menus->register('promotions', [
            'name' => 'Coupons', 'icon' => 'menu-icon tf-icons bx bx-collection',
            'route' => 'admin.coupons.index', 'active' => ['admin.coupons.*', 'admin.coupon-redemptions.*'],
            'children' => [
                ['name' => 'Coupons', 'route' => 'admin.coupons.index', 'active' => ['admin.coupons.*']],
                ['name' => 'Coupon Redemptions', 'route' => 'admin.coupon-redemptions.index', 'active' => ['admin.coupon-redemptions.*']],
            ],
        ], 700);
    }
}
