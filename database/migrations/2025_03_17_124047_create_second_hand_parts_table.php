<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('second_hand_parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_name');
            $table->string('seller');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['Available', 'Sold']);
            $table->enum('condition', ['New', 'Used'])->default('Used');
            $table->text('description')->nullable();
            $table->timestamp('listing_date')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_hand_parts');
    }
};
