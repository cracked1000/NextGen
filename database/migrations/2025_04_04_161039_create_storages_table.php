<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->boolean('is_nvme')->default(false); // Changed 'nvme' to 'is_nvme' for consistency
            $table->integer('capacity')->nullable(); // Added from previous conversations
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storages');
    }
};