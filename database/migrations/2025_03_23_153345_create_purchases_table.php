<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('Zipcode')->nullable();
            $table->string('payment_option'); 
            $table->string('card_details')->nullable();
            $table->boolean('verify_product')->default(false); // Yes/No
            $table->decimal('shipping_charges', 8, 2)->default(0.00);
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();

            $table->foreign('part_id')->references('id')->on('second_hand_parts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};