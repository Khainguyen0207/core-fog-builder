<?php

namespace Database\Seeders;

use App\Models\IpLog;
use Illuminate\Database\Seeder;

class IpLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IpLog::create([
            'ip' => '183.80.111.9',
            'iso_code' => 'VN',
            'country' => 'Vietnam',
            'city' => 'Ho Chi Minh City',
            'state' => 'SG',
            'state_name' => 'Ho Chi Minh',
            'postal_code' => '700000',
            'currency' => 'VND',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ]);
    }
}
