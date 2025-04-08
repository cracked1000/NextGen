<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gpus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('pcie_version');
            $table->integer('power_requirement');
            $table->integer('length');
            $table->integer('height');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gpus');
    }
};