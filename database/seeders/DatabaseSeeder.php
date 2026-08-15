<?php

namespace Database\Seeders;

use App\Plugins\PluginManager;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            SettingSeeder::class,
            MembershipSettingSeeder::class,
        ]);

        $plugins = app(PluginManager::class);
        $plugins->forgetCachedState();

        $pluginSeeders = [
            'figure-admin/catalog' => [CategorySeeder::class, ServiceSeeder::class],
            'figure-admin/workforce' => [StaffSeeder::class],
            'figure-admin/booking' => [BookingSeeder::class],
            'figure-admin/payments' => [PaymentSettingSeeder::class],
            'figure-admin/promotions' => [CouponSeeder::class, CouponApplicableSeeder::class, CouponRedemptionSeeder::class],
            'figure-admin/communications' => [EmailTemplateSeeder::class],
            'figure-admin/cms' => [BlogSeeder::class],
        ];

        foreach ($pluginSeeders as $packageName => $seeders) {
            if ($plugins->isEnabled($packageName)) {
                $this->call($seeders);
            }
        }
    }
}
