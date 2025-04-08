<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('socket_type');
            $table->integer('power_requirement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpus');
    }
};