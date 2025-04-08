<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motherboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('socket_type');
            $table->string('ram_type');
            $table->integer('ram_speed');
            $table->string('form_factor');
            $table->integer('ram_slots');
            $table->integer('sata_slots');
            $table->integer('m2_slots');
            $table->boolean('m2_nvme_support')->default(false);
            $table->float('pcie_version');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motherboards');
    }
};