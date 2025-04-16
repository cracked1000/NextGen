<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique(); // Unique quotation number
            $table->unsignedBigInteger('user_id'); // User associated with the quotation
            $table->unsignedBigInteger('build_id')->nullable(); // Build associated with the quotation (for build purchases)
            $table->unsignedBigInteger('quotation_request_id')->nullable(); // Quotation request ID (for Quotation Generator)
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('build_id')->references('id')->on('builds')->onDelete('cascade');
            $table->foreign('quotation_request_id')->references('id')->on('quotation_requests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};