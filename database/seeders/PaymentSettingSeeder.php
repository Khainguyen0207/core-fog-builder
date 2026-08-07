<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    public function run(): void
    {
        PaymentSetting::query()->delete();

        PaymentSetting::query()->insert([
            [
                'id' => 1,
                'provider_name' => 'sepay',
                'is_active' => 1,
                'config' => json_encode([
                    'api_token' => config('sepay.api_token', ''),
                ], true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'provider_name' => 'momo',
                'is_active' => 0,
                'config' => json_encode([
                    'data',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'provider_name' => 'binance',
                'is_active' => 0,
                'config' => json_encode([
                    'data',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
