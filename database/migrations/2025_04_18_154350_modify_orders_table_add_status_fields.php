<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add new boolean fields if they don't exist
            if (!Schema::hasColumn('orders', 'is_accepted')) {
                $table->boolean('is_accepted')->default(false)->after('status');
            }
            if (!Schema::hasColumn('orders', 'is_shipped')) {
                $table->boolean('is_shipped')->default(false)->after('is_accepted');
            }
            if (!Schema::hasColumn('orders', 'is_received')) {
                $table->boolean('is_received')->default(false)->after('is_shipped');
            }
            if (!Schema::hasColumn('orders', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('verify_product');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('orders', 'is_accepted')) {
                $table->dropColumn('is_accepted');
            }
            if (Schema::hasColumn('orders', 'is_shipped')) {
                $table->dropColumn('is_shipped');
            }
            if (Schema::hasColumn('orders', 'is_received')) {
                $table->dropColumn('is_received');
            }
            if (Schema::hasColumn('orders', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });
    }
};