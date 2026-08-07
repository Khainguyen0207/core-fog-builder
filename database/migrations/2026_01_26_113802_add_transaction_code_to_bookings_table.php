<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('transaction_code')->nullable()->index()->after('note');
            $table->string('payment_method');

            $table->foreign('transaction_code')
                ->references('transaction_code')
                ->on('transactions')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['transaction_code']);
            $table->dropColumn('transaction_code');
            $table->dropColumn('payment_method');
        });
    }
};
