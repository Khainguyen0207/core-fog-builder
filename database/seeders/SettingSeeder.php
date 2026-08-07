<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $settings = [
            [
                'key' => 'work_time_monday',
                'value' => '08:00 - 22:00',
                'description' => 'Work time for Monday (08:00 - 22:00)',
            ],
            [
                'key' => 'work_time_tuesday',
                'value' => '08:00 - 22:00',
                'description' => 'Work time for Tuesday (08:00 - 22:00)',
            ],
            [
                'key' => 'work_time_wednesday',
                'value' => '08:00 - 22:00',
                'description' => 'Work time for Wednesday (08:00 - 22:00)',
            ],
            [
                'key' => 'work_time_thursday',
                'value' => '08:00 - 22:00',
                'description' => 'Work time for Thursday (08:00 - 22:00)',
            ],
            [
                'key' => 'work_time_friday',
                'value' => '08:00 - 22:00',
                'description' => 'Work time for Friday (08:00 - 22:00)',
            ],
            [
                'key' => 'work_time_saturday',
                'value' => '08:00 - 12:00',
                'description' => 'Work time for Saturday (08:00 - 12:00)',
            ],
            [
                'key' => 'work_time_sunday',
                'value' => '00:00 - 00:00',
                'description' => 'Work time for Sunday (00:00 - 00:00)',
            ],
            [
                'key' => 'max_active_staff',
                'value' => 2,
                'description' => 'Max active staff in session',
            ],
            [
                'key' => 'sepay_api_token',
                'value' => config('payment.sepay.api_token', ''),
                'description' => 'SePay API token',
            ],
            [
                'key' => 'receiver_name',
                'value' => 'Nguyễn Trọng Khải',
                'description' => 'Receiver name',
            ],
            [
                'key' => 'bank_name',
                'value' => 'mbbank',
                'description' => 'Bank name',
            ],
            [
                'key' => 'account_number',
                'value' => '0989060084',
                'description' => 'Account number',
            ],
            [
                'key' => 'payment_provider',
                'value' => 'sepay',
                'description' => 'Payment provider',
            ],
            [
                'key' => 'system_hotline',
                'value' => '0989060084',
                'description' => 'System hotline',
            ],
            [
                'key' => 'system_address',
                'value' => '123 Đường ABC, Quận 2, TP. HCM',
                'description' => 'System address',
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://www.facebook.com/ntkhai2005',
                'description' => 'Social Facebook URL',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://www.instagram.com/',
                'description' => 'Social Instagram URL',
            ],
            [
                'key' => 'social_thread',
                'value' => 'https://www.threads.net/',
                'description' => 'Social Thread URL',
            ],
            [
                'key' => 'social_tiktok',
                'value' => 'https://www.tiktok.com/',
                'description' => 'Social Tiktok URL',
            ],
            [
                'key' => 'telegram_bot_token',
                'value' => config('telegram.bots.mybot.token', ''),
                'description' => 'Telegram Bot Token',
            ],
            [
                'key' => 'telegram_webhook_url',
                'value' => config('telegram.bots.mybot.webhook_url', ''),
                'description' => 'Telegram Webhook URL',
            ],
            [
                'key' => 'telegram_chat_id',
                'value' => config('telegram.bots.mybot.chat_id', ''),
                'description' => 'Telegram Chat ID',
            ],
            [
                'key' => 'is_active_telegram',
                'value' => false,
                'description' => 'Telegram is active',
            ],
            [
                'key' => 'is_active_payment',
                'value' => false,
                'description' => 'Payment is active',
            ]
        ];

        Setting::query()->truncate();

        Setting::query()->insert($settings);
    }
}
