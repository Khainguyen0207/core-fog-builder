<?php

namespace Modules\Promotions\Providers;

use App\Table\Configs\TableConfig;
use Illuminate\Support\ServiceProvider;
use Modules\Promotions\Admin\Tables\CouponApplicableTable;
use Modules\Promotions\Admin\Tables\CouponRedemptionTable;
use Modules\Promotions\Admin\Tables\CouponTable;

class PromotionsServiceProvider extends ServiceProvider
{
    public function boot(TableConfig $tables): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'promotions');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        $tables->register('coupons', CouponTable::class);
        $tables->register('coupon-applicables', CouponApplicableTable::class);
        $tables->register('coupon-redemptions', CouponRedemptionTable::class);
    }
}
