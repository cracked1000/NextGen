<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->unsignedBigInteger('cpu_id');
            $table->unsignedBigInteger('motherboard_id');
            $table->unsignedBigInteger('gpu_id');
            $table->unsignedBigInteger('ram_id');
            $table->unsignedBigInteger('storage_id');
            $table->unsignedBigInteger('power_supply_id');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
            
            // Add only the foreign key for users since we know that table exists
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // The other foreign keys will be added after their tables are created
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};