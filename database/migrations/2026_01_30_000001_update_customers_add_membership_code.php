<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('membership', 'membership_code');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('total_spent', 18, 2)->default(0)->after('membership_code');
            $table->index('membership_code');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['membership_code']);
            $table->dropColumn('total_spent');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('membership_code', 'membership');
        });
    }
};
