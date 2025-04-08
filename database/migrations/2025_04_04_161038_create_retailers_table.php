<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('retailers')) {
            Schema::create('retailers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('website');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('retailers');
    }
};