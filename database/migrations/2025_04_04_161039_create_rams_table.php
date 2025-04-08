<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ensure this is present
            $table->string('ram_type');
            $table->integer('ram_speed');
            $table->integer('stick_count');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rams');
    }
};
