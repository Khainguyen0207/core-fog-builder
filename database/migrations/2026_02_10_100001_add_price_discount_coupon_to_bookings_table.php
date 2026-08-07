<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->default(0)->after('total_price');
            $table->decimal('discount', 12, 2)->default(0)->after('price');
            $table->string('coupon_code')->nullable()->after('discount');
        });

        // Backfill: price = total_price, discount = 0
        DB::table('bookings')->update([
            'price' => DB::raw('total_price'),
            'discount' => 0,
        ]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['price', 'discount', 'coupon_code']);
        });
    }
};
