<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('part_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('country');
            $table->string('province');
            $table->string('district');
            $table->string('zipcode')->nullable();
            $table->string('payment_option');
            $table->string('stripe_payment_id')->nullable();
            $table->decimal('component_price', 10, 2);
            $table->boolean('verify_product');
            $table->decimal('verify_cost', 10, 2);
            $table->decimal('shipping_charges', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->string('shipping_address')->nullable();
            $table->enum('payment_status', ['Pending', 'Paid', 'Failed'])->default('Pending');
            $table->timestamp('order_date')->nullable();
            $table->timestamps();
        });

        // Add foreign key constraints in a separate step for safety
        if (Schema::hasTable('users')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('second_hand_parts')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('part_id')->references('id')->on('second_hand_parts')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasTable('users')) {
                $table->dropForeign(['customer_id']);
            }
            if (Schema::hasTable('second_hand_parts')) {
                $table->dropForeign(['part_id']);
            }
        });

        Schema::dropIfExists('orders');
    }
};