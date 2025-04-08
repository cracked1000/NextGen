<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('purchases');
    }

    public function down(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address')->nullable();
            $table->string('Zipcode')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('optional_phone_number')->nullable();
            $table->string('email');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();

            $table->foreign('part_id')->references('id')->on('second_hand_parts')->onDelete('cascade');
        });
    }
};