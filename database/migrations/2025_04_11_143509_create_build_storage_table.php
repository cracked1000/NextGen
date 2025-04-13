<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('build_storage', function (Blueprint $table) {
            $table->unsignedBigInteger('build_id');
            $table->unsignedBigInteger('storage_id');
            $table->primary(['build_id', 'storage_id']);
            $table->foreign('build_id')->references('id')->on('builds')->onDelete('cascade');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_storage');
    }
};