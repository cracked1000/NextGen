<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->dropColumn('ram_id');
            $table->dropColumn('storage_id');
        });
    }

    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->foreignId('ram_id')->nullable()->constrained('rams')->onDelete('set null');
            $table->foreignId('storage_id')->nullable()->constrained('storages')->onDelete('set null');
        });
    }
};