<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('power_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('wattage');
            $table->string('form_factor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('power_supplies');
    }
};