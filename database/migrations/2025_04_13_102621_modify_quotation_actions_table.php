<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotation_actions', function (Blueprint $table) {
            $table->string('quotation_number')->unique()->after('id');
            $table->string('source')->nullable()->after('quotation_number'); // 'Build PC' or 'Quotation Generator'
            $table->unsignedBigInteger('build_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('quotation_request_id')->nullable()->after('build_id');

            // Foreign keys
            $table->foreign('build_id')->references('id')->on('builds')->onDelete('cascade');
            $table->foreign('quotation_request_id')->references('id')->on('quotation_requests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('quotation_actions', function (Blueprint $table) {
            $table->dropForeign(['build_id']);
            $table->dropForeign(['quotation_request_id']);
            $table->dropColumn(['quotation_number', 'source', 'build_id', 'quotation_request_id']);
        });
    }
};