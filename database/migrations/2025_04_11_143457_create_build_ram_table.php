<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('build_ram', function (Blueprint $table) {
            $table->unsignedBigInteger('build_id');
            $table->unsignedBigInteger('ram_id');
            $table->primary(['build_id', 'ram_id']);
            $table->foreign('build_id')->references('id')->on('builds')->onDelete('cascade');
            $table->foreign('ram_id')->references('id')->on('rams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_ram');
    }
};